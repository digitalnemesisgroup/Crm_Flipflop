<div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
            {{ $slot }}
        </table>
    </div>
</div>
