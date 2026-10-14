{{-- Tab 6: Catatan --}}
<div class="drv-form-card">

    <div class="form-section-title">
        <i class="fas fa-book"></i>
        <span data-en="Notes">Catatan</span>
    </div>

    <div class="mb-3">
        <label for="notes" class="form-label form-label-sm" data-en="Notes (optional)">Catatan (opsional)</label>
        <textarea name="notes" id="notes" rows="6"
                  class="form-control form-control-sm {{ $hasErr('notes') }}"
                  placeholder="{{ __('Informasi tambahan, catatan khusus, dsb.') }}">{{ $old('notes') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if($isEdit)
    <div class="mt-4 pt-3 border-top row g-2" style="font-size:.8rem;color:var(--text-muted)">
        <div class="col-md-4">
            <div class="info-label" data-en="Created by">Dibuat oleh</div>
            <div>{{ $driver->createdBy?->name ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label" data-en="Created on">Dibuat pada</div>
            <div>{{ $driver->created_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label" data-en="Last modified">Terakhir diubah</div>
            <div>{{ $driver->updated_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
    </div>
    @endif

</div>