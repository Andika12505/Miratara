// public/js/admin/product-form-enhancements.js

class ProductFormEnhancer {
    constructor() {
        this.initializeForm();
        this.setupEventListeners();
        this.setupImagePreview();
        this.setupFormValidation();
        this.setupMetadataHelpers();
    }

    initializeForm() {
        // Auto-generate slug from product name
        this.nameInput = document.getElementById('name');
        this.slugInput = document.getElementById('slug');
        
        if (this.nameInput && this.slugInput) {
            this.nameInput.addEventListener('input', () => this.generateSlug());
        }
    }

    generateSlug() {
        const name = this.nameInput.value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
        this.slugInput.value = slug;
    }

    setupEventListeners() {
        // Price formatting
        const priceInput = document.getElementById('price');
        if (priceInput) {
            priceInput.addEventListener('input', this.formatPrice.bind(this));
        }

        // Stock validation
        const stockInput = document.getElementById('stock');
        if (stockInput) {
            stockInput.addEventListener('input', this.validateStock.bind(this));
        }

        // Image file validation
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', this.validateImage.bind(this));
        }

        // Form submission validation
        const form = document.getElementById('productForm');
        if (form) {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }

        // Checkbox group helpers
        this.setupCheckboxGroupHelpers();
    }

    formatPrice(event) {
        let value = event.target.value;
        // Remove non-numeric characters except decimal point
        value = value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        
        // Limit decimal places to 2
        if (parts[1] && parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].slice(0, 2);
        }
        
        event.target.value = value;
    }

    validateStock(event) {
        let value = event.target.value;
        // Remove non-numeric characters
        value = value.replace(/[^0-9]/g, '');
        
        // Ensure it's within reasonable bounds
        if (parseInt(value) > 99999) {
            value = '99999';
        }
        
        event.target.value = value;
    }

    validateImage(event) {
        const file = event.target.files[0];
        const errorDiv = document.getElementById('image-error');
        
        // Remove existing error
        if (errorDiv) {
            errorDiv.remove();
        }

        if (!file) return;

        // Check file type
        if (!file.type.includes('png')) {
            this.showImageError('Hanya file PNG yang diperbolehkan.');
            event.target.value = '';
            return;
        }

        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            this.showImageError('Ukuran file maksimal 2MB.');
            event.target.value = '';
            return;
        }

        // Show preview
        this.showImagePreview(file);
    }

    showImageError(message) {
        const imageInput = document.getElementById('image');
        const errorDiv = document.createElement('div');
        errorDiv.id = 'image-error';
        errorDiv.className = 'text-danger mt-1';
        errorDiv.textContent = message;
        imageInput.parentNode.appendChild(errorDiv);
    }

    showImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove existing preview
            const existingPreview = document.getElementById('image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }

            // Create preview
            const imageInput = document.getElementById('image');
            const preview = document.createElement('div');
            preview.id = 'image-preview';
            preview.className = 'mt-2';
            preview.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${e.target.result}" alt="Preview" style="width: 100px; height: 100px; object-fit: cover;" class="img-thumbnail me-2">
                    <div>
                        <p class="mb-1 text-success">✓ File valid: ${file.name}</p>
                        <small class="text-muted">Ukuran: ${(file.size / 1024).toFixed(1)} KB</small>
                    </div>
                </div>
            `;
            imageInput.parentNode.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }

    setupImagePreview() {
        // This method is called in constructor, implementation above
    }

    setupFormValidation() {
        // Real-time validation feedback
        const requiredFields = ['name', 'slug', 'category_id', 'price', 'stock'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.addEventListener('blur', () => this.validateField(field));
                field.addEventListener('input', () => this.clearFieldError(field));
            }
        });
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        switch (field.id) {
            case 'name':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Nama produk wajib diisi.';
                } else if (value.length < 3) {
                    isValid = false;
                    errorMessage = 'Nama produk minimal 3 karakter.';
                }
                break;
            case 'slug':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Slug wajib diisi.';
                } else if (!/^[a-z0-9-]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.';
                }
                break;
            case 'category_id':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Kategori wajib dipilih.';
                }
                break;
            case 'price':
                if (!value || parseFloat(value) <= 0) {
                    isValid = false;
                    errorMessage = 'Harga harus lebih dari 0.';
                } else if (parseFloat(value) > 999999.99) {
                    isValid = false;
                    errorMessage = 'Harga maksimal 999,999.99.';
                }
                break;
            case 'stock':
                if (!value || parseInt(value) < 0) {
                    isValid = false;
                    errorMessage = 'Stok tidak boleh negatif.';
                }
                break;
        }

        this.showFieldValidation(field, isValid, errorMessage);
    }

    showFieldValidation(field, isValid, errorMessage) {
        // Remove existing feedback
        const existingFeedback = field.parentNode.querySelector('.field-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }

        // Add appropriate classes
        field.classList.remove('is-valid', 'is-invalid');
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');

        // Show error message if invalid
        if (!isValid && errorMessage) {
            const feedback = document.createElement('div');
            feedback.className = 'field-feedback text-danger mt-1';
            feedback.textContent = errorMessage;
            field.parentNode.appendChild(feedback);
        }
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.parentNode.querySelector('.field-feedback');
        if (feedback) {
            feedback.remove();
        }
    }

    setupCheckboxGroupHelpers() {
        // Add "Select All" / "Clear All" buttons for checkbox groups
        const checkboxGroups = document.querySelectorAll('.checkbox-group');
        
        checkboxGroups.forEach(group => {
            this.addCheckboxGroupButtons(group);
        });
    }

    addCheckboxGroupButtons(group) {
        const checkboxes = group.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length === 0) return;

        // Create button container
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mb-2 d-flex gap-2';
        
        // Select All button
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'btn btn-outline-primary btn-sm';
        selectAllBtn.textContent = 'Select All';
        selectAllBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = true);
        });

        // Clear All button
        const clearAllBtn = document.createElement('button');
        clearAllBtn.type = 'button';
        clearAllBtn.className = 'btn btn-outline-secondary btn-sm';
        clearAllBtn.textContent = 'Clear All';
        clearAllBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = false);
        });

        buttonContainer.appendChild(selectAllBtn);
        buttonContainer.appendChild(clearAllBtn);
        
        // Insert at the beginning of the group
        group.insertBefore(buttonContainer, group.firstChild);
    }

    setupMetadataHelpers() {
        // Add counter for selected items in each vibe attribute section
        const vibeCards = document.querySelectorAll('.card .card-body .checkbox-group');
        
        vibeCards.forEach(group => {
            this.addSelectionCounter(group);
        });
    }

    addSelectionCounter(group) {
        const checkboxes = group.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length === 0) return;

        // Create counter element
        const counter = document.createElement('div');
        counter.className = 'selection-counter text-muted mt-2';
        
        const updateCounter = () => {
            const checked = group.querySelectorAll('input[type="checkbox"]:checked').length;
            counter.textContent = `${checked} selected`;
            
            // Add warning if too many selected
            if (checked > 7) {
                counter.className = 'selection-counter text-warning mt-2';
            } else if (checked > 10) {
                counter.className = 'selection-counter text-danger mt-2';
            } else {
                counter.className = 'selection-counter text-muted mt-2';
            }
        };

        // Initial count
        updateCounter();

        // Add event listeners
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCounter);
        });

        group.appendChild(counter);
    }

    handleFormSubmit(event) {
        // Final validation before submit
        const requiredFields = ['name', 'slug', 'category_id', 'price', 'stock'];
        let hasErrors = false;

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                this.validateField(field);
                if (field.classList.contains('is-invalid')) {
                    hasErrors = true;
                }
            }
        });

        if (hasErrors) {
            event.preventDefault();
            
            // Show alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <strong>Error!</strong> Mohon perbaiki field yang bermasalah sebelum submit.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const form = document.getElementById('productForm');
            form.insertBefore(alert, form.firstChild);
            
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new ProductFormEnhancer();
});