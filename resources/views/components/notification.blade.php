<div class="notification border border-slate-100 shadow-xl" 
    role="alert"
    data-duration="5000"
    x-init-toast>
    <div class="notification-header">
        <h4 class="text-base font-bold text-slate-800 leading-tight">
            {{ $header }}
        </h4>

        <button class="close-notification active:translate-y-[1px]" aria-label="Close notification">
            <i class="fa-solid fa-xmark text-xs" style="color: rgb(30, 48, 80);"></i>
        </button>
    </div>
    
    <div class="notification-body text-sm text-slate-600 leading-relaxed mb-1">
        {{ $body }}
    </div>
    
    @if(isset($footer))
        <div class="notification-footer text-xs text-slate-400 text-right">
            {{ $footer }}
        </div>
    @endif
</div>