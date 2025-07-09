<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductGrid extends Component
{
    public $products;
    public $showDiscount;
    public $useFormCart;
    public $emptyMessage;
    public $emptyButtonText;
    public $emptyButtonClass;
    public $buttonText;
    public $outOfStockText;

    public function __construct(
        $products,
        $showDiscount = false,
        $useFormCart = false,
        $emptyMessage = 'No products found.',
        $emptyButtonText = 'View All Products',
        $emptyButtonClass = 'btn btn-primary',
        $buttonText = 'ADD TO BAG',
        $outOfStockText = 'OUT OF STOCK'
    ) {
        $this->products = $products;
        $this->showDiscount = $showDiscount;
        $this->useFormCart = $useFormCart;
        $this->emptyMessage = $emptyMessage;
        $this->emptyButtonText = $emptyButtonText;
        $this->emptyButtonClass = $emptyButtonClass;
        $this->buttonText = $buttonText;
        $this->outOfStockText = $outOfStockText;
    }

    public function render()
    {
        return view('components.product-grid');
    }
}