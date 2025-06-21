<div>

    <flux:heading class="text-xl">Project Information</flux:heading>

    <div class="mt-4 flex flex-col gap-2 border-2 border-zinc-500 rounded-md p-4">
        <flux:text class="text-lg"><span class="font-bold text-zinc-200">Project Name:</span>
            {{ $projectInfo->project_name }}
        </flux:text>
        <flux:text class="text-lg"><span class="font-bold text-zinc-200">Project Location:</span>
            {{ $projectInfo->project_location }}
        </flux:text>
        <flux:text class="text-lg"><span class="font-bold text-zinc-200">Project ID:</span>
            {{ $projectInfo->project_id }}</flux:text>
        <flux:text class="text-lg"><span class="font-bold text-zinc-200">Contractor:</span>
            {{ $projectInfo->contractor }}</flux:text>
        <flux:text class="text-lg"><span class="font-bold text-zinc-200">Project Type:</span>
            {{ $projectInfo->project_type }}
        </flux:text>
    </div>
</div>
