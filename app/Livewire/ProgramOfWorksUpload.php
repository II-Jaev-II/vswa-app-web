<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\ProgramOfWork;
use Box\Spout\Common\Exception\SpoutException;
use Flux\Flux;
use Illuminate\Support\Facades\Log;

class ProgramOfWorksUpload extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $subproject_id;

    protected $rules = [
        'excelFile' => 'required|file|mimes:xls,xlsx|max:20480',
    ];

    public function mount($subproject_id)
    {
        $this->subproject_id = $subproject_id;
    }

    public function updatedExcelFile()
    {
        $this->validate();

        $path = $this->excelFile->store('excel-uploads');
        $fullPath = Storage::path($path);

        try {
            $reader = ReaderEntityFactory::createReaderFromFile($fullPath);
            $reader->open($fullPath);

            $isHeader = true;

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $cells = $row->toArray();

                    if ($isHeader) {
                        $isHeader = false;

                        // Define exactly what you expect in the 1st row:
                        $expected = ['Item No.', 'Scope of Work', 'Quantity', 'Unit'];

                        // Only look at the first four cells:
                        $headerSlice = array_slice($cells, 0, count($expected));

                        if ($headerSlice !== $expected) {
                            // Stop processing and show an error in the form
                            $this->addError('excelFile', 'Please use the provided template.');
                            // make sure to close out the reader so finally still runs
                            $reader->close();
                            return;
                        }

                        // skip the header row
                        continue;
                    }

                    // … if we got here, header was valid, so import each data row …
                    ProgramOfWork::create([
                        'subproject_id' => $this->subproject_id,
                        'item_no' => $cells[0] ?? null,
                        'scope_of_work' => $cells[1] ?? null,
                        'quantity' => $cells[2] ?? 0,
                        'unit' => $cells[3] ?? null,
                    ]);
                }
            }

            $reader->close();
        } catch (SpoutException $e) {
            Log::error('Spout import error: ' . $e->getMessage());
            $this->addError('excelFile', 'Could not read this Excel file.');
        } catch (\Exception $e) {
            Log::error('General import error: ' . $e->getMessage());
            $this->addError('excelFile', 'Unexpected error: ' . $e->getMessage());
        } finally {
            Storage::delete($path);
        }
    }

    public function destroy($id)
    {
        ProgramOfWork::findOrFail($id)->delete();
        Flux::modal('delete-item-' . $id)->close();
        session()->flash('message', 'Item deleted successfully.');
    }

    public function render()
    {
        $items = ProgramOfWork::where('subproject_id', $this->subproject_id)->orderBy('item_no')->get();

        return view('livewire.program-of-works-upload', compact('items'));
    }
}
