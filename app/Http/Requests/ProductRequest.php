<?php
// app/Http/Requests/ProductRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add proper authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            // Basic product fields
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|min:3',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                $productId ? Rule::unique('products')->ignore($productId) : 'unique:products,slug'
            ],
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:png|max:2048',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock' => 'required|integer|min:0|max:99999',
            'origin' => 'nullable|string|max:100|in:Indonesia,China,Vietnam,India,USA,Turkey,Bangladesh',
            
            // Vibe attributes validation
            'vibe_occasion' => 'nullable|array|max:10',
            'vibe_occasion.*' => 'string|in:casual,formal,party,work,sport,vacation,daily',
            
            'vibe_style' => 'nullable|array|max:10',
            'vibe_style.*' => 'string|in:vintage,modern,classic,trendy,minimalist,bohemian,streetwear',
            
            'vibe_material' => 'nullable|array|max:10',
            'vibe_material.*' => 'string|in:cotton,polyester,wool,silk,linen,denim,leather,viscose',
            
            'vibe_color_tone' => 'nullable|array|max:10',
            'vibe_color_tone.*' => 'string|in:bright,pastel,dark,neutral,earth,neon,metallic',
            
            'vibe_fit' => 'nullable|array|max:10',
            'vibe_fit.*' => 'string|in:slim,regular,loose,oversized,fitted,relaxed',
            
            'vibe_pattern' => 'nullable|array|max:10',
            'vibe_pattern.*' => 'string|in:solid,striped,floral,geometric,abstract,polka_dots,checkered',
            
            'vibe_neckline' => 'nullable|array|max:10',
            'vibe_neckline.*' => 'string|in:round,v_neck,crew,scoop,high_neck,off_shoulder,halter',
            
            'vibe_sleeve_length' => 'nullable|array|max:10',
            'vibe_sleeve_length.*' => 'string|in:sleeveless,short_sleeve,long_sleeve,3_quarter,cap_sleeve',
            
            // General tags validation
            'general_tags' => 'nullable|array|max:10',
            'general_tags.*' => 'string|in:comfortable,elegant,sporty,sexy,professional,casual_wear,evening_wear',
            
            'is_active' => 'boolean',
            'clear_image' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            
            'name.required' => 'Nama produk wajib diisi.',
            'name.min' => 'Nama produk minimal 3 karakter.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            
            'slug.required' => 'Slug produk wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan produk lain.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat PNG.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'price.max' => 'Harga maksimal 999,999.99.',
            
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'stock.max' => 'Stok maksimal 99,999.',
            
            'origin.in' => 'Asal produk harus dipilih dari daftar yang tersedia.',
            
            // Vibe attributes messages
            'vibe_occasion.array' => 'Occasion harus berupa pilihan.',
            'vibe_occasion.max' => 'Maksimal 10 pilihan occasion.',
            'vibe_occasion.*.in' => 'Pilihan occasion tidak valid.',
            
            'vibe_style.array' => 'Style harus berupa pilihan.',
            'vibe_style.max' => 'Maksimal 10 pilihan style.',
            'vibe_style.*.in' => 'Pilihan style tidak valid.',
            
            'vibe_material.array' => 'Material harus berupa pilihan.',
            'vibe_material.max' => 'Maksimal 10 pilihan material.',
            'vibe_material.*.in' => 'Pilihan material tidak valid.',
            
            'vibe_color_tone.array' => 'Color tone harus berupa pilihan.',
            'vibe_color_tone.max' => 'Maksimal 10 pilihan color tone.',
            'vibe_color_tone.*.in' => 'Pilihan color tone tidak valid.',
            
            'vibe_fit.array' => 'Fit harus berupa pilihan.',
            'vibe_fit.max' => 'Maksimal 10 pilihan fit.',
            'vibe_fit.*.in' => 'Pilihan fit tidak valid.',
            
            'vibe_pattern.array' => 'Pattern harus berupa pilihan.',
            'vibe_pattern.max' => 'Maksimal 10 pilihan pattern.',
            'vibe_pattern.*.in' => 'Pilihan pattern tidak valid.',
            
            'vibe_neckline.array' => 'Neckline harus berupa pilihan.',
            'vibe_neckline.max' => 'Maksimal 10 pilihan neckline.',
            'vibe_neckline.*.in' => 'Pilihan neckline tidak valid.',
            
            'vibe_sleeve_length.array' => 'Sleeve length harus berupa pilihan.',
            'vibe_sleeve_length.max' => 'Maksimal 10 pilihan sleeve length.',
            'vibe_sleeve_length.*.in' => 'Pilihan sleeve length tidak valid.',
            
            'general_tags.array' => 'General tags harus berupa pilihan.',
            'general_tags.max' => 'Maksimal 10 pilihan general tags.',
            'general_tags.*.in' => 'Pilihan general tags tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'name' => 'nama produk',
            'slug' => 'slug',
            'description' => 'deskripsi',
            'image' => 'gambar',
            'price' => 'harga',
            'stock' => 'stok',
            'origin' => 'asal produk',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // You can add custom logic here if needed
        parent::failedValidation($validator);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox boolean values
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'clear_image' => $this->boolean('clear_image'),
        ]);

        // Clean up slug
        if ($this->filled('slug')) {
            $this->merge([
                'slug' => strtolower(preg_replace('/[^a-z0-9-]/', '', $this->slug))
            ]);
        }

        // Remove empty arrays from vibe attributes to avoid validation errors
        $vibeFields = [
            'vibe_occasion', 'vibe_style', 'vibe_material', 'vibe_color_tone',
            'vibe_fit', 'vibe_pattern', 'vibe_neckline', 'vibe_sleeve_length'
        ];

        foreach ($vibeFields as $field) {
            if ($this->has($field) && empty($this->input($field))) {
                $this->offsetUnset($field);
            }
        }

        // Remove empty general_tags
        if ($this->has('general_tags') && empty($this->input('general_tags'))) {
            $this->offsetUnset('general_tags');
        }
    }
}