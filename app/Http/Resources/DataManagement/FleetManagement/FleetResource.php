<?php

namespace App\Http\Resources\DataManagement\FleetManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'vehicle_code'  => $this->vehicle_code,
            'police_number' => $this->police_number,
            'hull_number'   => $this->hull_number,

            'type'  => $this->whenLoaded('type',  fn() => ['id' => $this->type->id,  'name' => $this->type->name]),
            'brand' => $this->whenLoaded('brand', fn() => ['id' => $this->brand->id, 'name' => $this->brand->name]),
            'model' => $this->whenLoaded('model', fn() => ['id' => $this->model?->id, 'name' => $this->model?->name ?? $this->model_name]),

            'manufacture_year' => $this->manufacture_year,
            'color'            => $this->color,
            'vehicle_status'   => $this->vehicle_status,
            'status_label'     => $this->status_label,
            'status_color'     => $this->status_color,

            'chassis_number'  => $this->chassis_number,
            'engine_number'   => $this->engine_number,
            'load_capacity_ton' => $this->load_capacity_ton,

            'ownership_status' => $this->ownership_status,
            'ownership_label'  => $this->ownership_label,
            'last_odometer_km' => $this->last_odometer_km,

            'tax_expiry_date'       => $this->tax_expiry_date?->format('Y-m-d'),
            'kir_expiry_date'       => $this->kir_expiry_date?->format('Y-m-d'),
            'insurance_expiry_date' => $this->insurance_expiry_date?->format('Y-m-d'),

            'primary_photo' => $this->whenLoaded('photos', function () {
                $primary = $this->photos->firstWhere('is_primary', true) ?? $this->photos->first();
                return $primary ? $primary->url : null;
            }),

            'photos'    => $this->whenLoaded('photos',    fn() => $this->photos->map(fn($p) => [
                'id'             => $p->id,
                'category'       => $p->category,
                'category_label' => $p->category_label,
                'caption'        => $p->caption,
                'url'            => $p->url,
                'is_primary'     => $p->is_primary,
            ])),

            'documents' => $this->whenLoaded('documents', fn() => $this->documents->map(fn($d) => [
                'id'           => $d->id,
                'type'         => $d->document_type,
                'type_label'   => $d->type_label,
                'number'       => $d->document_number,
                'expiry_date'  => $d->expiry_date?->format('Y-m-d'),
                'is_expired'   => $d->is_expired,
                'expiring_soon' => $d->is_expiring_soon,
                'file_url'     => $d->file_url,
            ])),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}