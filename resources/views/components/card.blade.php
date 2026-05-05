<div class="bg-white rounded-xl shadow-lg overflow-hidden {{ $class ?? '' }}">
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-200 {{ $headerClass ?? '' }}">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6 {{ $bodyClass ?? '' }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 {{ $footerClass ?? '' }}">
            {{ $footer }}
        </div>
    @endif
</div>