<!-- resources/views/pages/products.blade.php -->
<style>
    /* Add this CSS to your stylesheet or in a <style> tag */

    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 20px;
    }

    .product-image {
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
        width: 50%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.05);
        /* Zooms in the image */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Adds a shadow effect */
    }
</style>
<!-- resources/views/layout/navbar.blade.php -->


@extends('layout.app')

@section('content')
    <nav class="navbar navbar-light bg-light">
        <span class="navbar-brand mb-0 h5">
            <a href="{{ url('/cart') }}">Cart</a>
        </span>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm border-light" style="text-align:center;">
                                <!-- Ensure image is responsive and fits the card -->
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                    class="card-img-top img-fluid product-image"
                                    style="object-fit:fill; height: 200px; width:250px;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->title }}</h5>
                                    <p class="card-text">{{ $product->short_des }}</p>
                                    <p class="card-text mb-2">
                                        Price:
                                        @if ($product->discount)
                                            <span class="text-danger">${{ $product->discount_price }}</span>
                                            <small><del>${{ $product->price }}</del></small>
                                        @else
                                            ${{ $product->price }}
                                        @endif
                                    </p>
                                    <p class="card-text mb-3">Rating: {{ $product->star }} / 5</p>
                                    <!-- Pass product ID to AddToCart function -->
                                    <a href="javascript:void(0);" onclick="AddToCart({{ $product->id }})"
                                        class="btn btn-primary mt-auto">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        async function AddToCart(productId) {
            try {
                let p_qty = 1; // Default quantity set to 1
                $(".preloader").delay(90).fadeIn(100).removeClass('loaded');

                let res = await axios.post("/CreateCartList", {
                    id: productId, // Send product ID correctly
                    qty: p_qty // Quantity set to 1
                });

                $(".preloader").delay(90).fadeOut(100).addClass('loaded');
                if (res.status === 200) {
                    alert("Product added to cart successfully");
                }
            } catch (e) {
                if (e.response && e.response.status === 401) {
                    sessionStorage.setItem("last_location", window.location.href);
                    window.location.href = "/login";
                } else {
                    alert("Error adding product to cart");
                }
            }
        }
    </script>
@endsection
