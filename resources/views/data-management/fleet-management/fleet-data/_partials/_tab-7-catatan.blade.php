<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-book"></i>
        <span data-en="Additional Notes">Catatan Tambahan</span>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" data-en="Notes">Catatan</label>
            <textarea name="notes"
                      class="form-control {{ $hasErr('notes') }}"
                      rows="6"
                      placeholder="Catatan kondisi kendaraan, riwayat perbaikan, atau informasi lain yang perlu diketahui…">{{ $old('notes') }}</textarea>
            <div class="form-text" data-en="Use this field to record special conditions, modifications, or other important information.">
                Gunakan kolom ini untuk mencatat kondisi khusus, modifikasi, atau informasi penting lainnya.
            </div>
            <div class="invalid-feedback">{{ $err('notes') }}</div>
        </div>

        @if($isEdit)
        <div class="col-12">
            <div class="rounded p-3" style="background:var(--surface-2);font-size:.8rem;color:var(--text-muted)">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="info-label" data-en="Created by">Dibuat oleh</div>
                        <div class="info-value">{{ $fleet->createdBy?->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label" data-en="Created on">Dibuat pada</div>
                        <div class="info-value">{{ $fleet->created_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label" data-en="Last modified">Terakhir diubah</div>
                        <div class="info-value">{{ $fleet->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

</div>