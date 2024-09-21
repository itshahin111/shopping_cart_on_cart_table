@extends('layout.app')

@section('content')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
                        <li class="breadcrumb-item"><a href="#">This Page</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>

    <div class="mt-5">
        <div class="container my-5">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive shop_cart_table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="byList">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="px-0">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                                Total: $ <span id="total"></span>
                                            </div>
                                            <div class="col-lg-8 col-md-6 text-start text-md-end">
                                                <button onclick="CheckOut()" class="btn btn-line-fill btn-sm"
                                                    type="submit">Check Out</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function CartList() {
            try {
                let res = await axios.get(`/CartList`);
                $("#byList").empty();

                res.data['data'].forEach((item, i) => {
                    let EachItem = `<tr>
                        <td class="product-thumbnail"><img src="{{ asset('storage/') }}/${item['product']['image']}" alt="product"></td>
                        <td class="product-name">${item['product']['title']}</td>
                        <td class="product-quantity">
                            <button class="btn btn-sm btn-outline-secondary decrease-qty" data-id="${item['product_id']}">-</button>
                            <span id="qty-${item['product_id']}">${item['qty']}</span>
                            <button class="btn btn-sm btn-outline-secondary increase-qty" data-id="${item['product_id']}">+</button>
                        </td>
                        <td class="product-subtotal">$<span id="price-${item['product_id']}">${item['price']}</span></td>
                        <td class="product-remove"><a class="remove" data-id="${item['product_id']}"><i class="ti-close"></i></a></td>
                    </tr>`;
                    $("#byList").append(EachItem);
                });

                await CartTotal(res.data['data']);
                $(".remove").on('click', function() {
                    let id = $(this).data('id');
                    RemoveCartList(id);
                });

                // Event listeners for increase and decrease buttons
                $(".increase-qty").on('click', function() {
                    let id = $(this).data('id');
                    UpdateCartQuantity(id, 'increase');
                });

                $(".decrease-qty").on('click', function() {
                    let id = $(this).data('id');
                    UpdateCartQuantity(id, 'decrease');
                });

            } catch (error) {
                console.error("Failed to fetch cart list:", error);
            }
        }

        async function CartTotal(data) {
            let Total = 0;
            data.forEach((item) => {
                Total += parseFloat(item['price']);
            });
            $("#total").text(Total.toFixed(2));
        }

        async function RemoveCartList(id) {
            try {
                $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
                let res = await axios.get("/DeleteCartList/" + id);
                $(".preloader").delay(90).fadeOut(100).addClass('loaded');
                if (res.status === 200) {
                    await CartList();
                } else {
                    alert("Request Failed");
                }
            } catch (error) {
                console.error("Failed to remove item from cart:", error);
                alert("An error occurred while removing the item.");
            }
        }

        async function UpdateCartQuantity(id, action) {
            try {
                let qtyElement = $(`#qty-${id}`);
                let priceElement = $(`#price-${id}`);
                let currentQty = parseInt(qtyElement.text());

                // Adjust quantity based on action
                let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;

                if (newQty <= 0) {
                    alert("Quantity cannot be less than 1.");
                    return;
                }

                // Update the quantity in the backend
                let res = await axios.post("/UpdateCartQuantity", {
                    product_id: id,
                    qty: newQty
                });

                if (res.status === 200) {
                    qtyElement.text(newQty);
                    priceElement.text(res.data.newPrice); // Assuming the new price is returned
                    await CartTotal(res.data['data']);
                } else {
                    alert("Failed to update quantity.");
                }
            } catch (error) {
                console.error("Failed to update cart quantity:", error);
                alert("An error occurred while updating the quantity.");
            }
        }

        async function CheckOut() {
            try {
                $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
                let res = await axios.get("/InvoiceCreate");
                $(".preloader").delay(90).fadeOut(100).addClass('loaded');

                if (res.status === 200) {
                    $("#paymentMethodModal").modal('show');
                    $("#paymentList").empty();
                    res.data['data'][0]['paymentMethod'].forEach((item) => {
                        let EachItem = `<tr>
                            <td><img class="w-50" src=${item['logo']} alt="product"></td>
                            <td><p>${item['name']}</p></td>
                            <td><a class="btn btn-danger btn-sm" href="${item['redirectGatewayURL']}">Pay</a></td>
                        </tr>`;
                        $("#paymentList").append(EachItem);
                    });
                } else {
                    alert("Request Failed");
                }
            } catch (error) {
                console.error("Failed to initiate checkout:", error);
                alert("An error occurred during checkout.");
            }
        }

        // Call the CartList function to load the cart items when the page is ready
        document.addEventListener('DOMContentLoaded', CartList);
    </script>
@endsection
