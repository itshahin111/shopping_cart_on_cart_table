@extends('layout.app')

@section('content')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
                        <li class="breadcrumb-item"><a href="#">Cart</a></li>
                    </ol>
                </div>
            </div>
        </div>
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
                                <!-- Cart items will be loaded here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="px-0">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                                Total: $<span id="total">0.00</span>
                                            </div>
                                            <div class="col-lg-8 col-md-6 text-start text-md-end">
                                                <button onclick="CheckOut()" class="btn btn-line-fill btn-sm">Check
                                                    Out</button>
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
                console.log("Cart data fetched:", res.data);

                let cartItems = res.data['data'];
                $("#byList").empty();

                cartItems.forEach((item, i) => {
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

                await CartTotal(cartItems);

                // Remove button
                $(".remove").on('click', function() {
                    let id = $(this).data('id');
                    DeleteCartList(id);
                });

                // Increase and decrease quantity buttons
                $(".increase-qty").on('click', function() {
                    let id = $(this).data('id');
                    UpdateCartQuantity(id, 'increase');
                    // alert response 

                    successToast("Quantity updated successfully");
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
            console.log("Total price calculated:", Total);
            $("#total").text(Total.toFixed(2));
        }

        async function UpdateCartQuantity(id, action) {
            try {
                let currentQty = parseInt($("#qty-" + id).text());
                let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;

                if (newQty < 1) return; // Prevent quantity from going below 1

                let res = await axios.post(`/UpdateCartQuantity`, {
                    product_id: id,
                    qty: newQty
                });
                console.log("Updated quantity response:", res.data);

                if (res.status === 200) {
                    // Update quantity and price in the UI
                    $("#qty-" + id).text(newQty);
                    $("#price-" + id).text(res.data.newPrice.toFixed(2));

                    // Recalculate and update the total price
                    await CartTotal(res.data.data);
                } else {
                    alert("Request Failed");
                }
            } catch (error) {
                console.error("Failed to update cart quantity:", error);
            }
        }
        async function DeleteCartList(id) {
            try {
                // Show a confirmation popup
                const userConfirmed = confirm("Are you sure you want to delete this item?");

                if (userConfirmed) {
                    let res = await axios.get("/DeleteCartList/" + id);
                    if (res.status === 200) {
                        // If deletion is successful, update the cart list
                        await CartList();
                        alert("Item has been deleted.");
                    } else {
                        alert("Request Failed");
                    }
                } else {
                    // User canceled the deletion
                    alert("Item deletion canceled.");
                }
            } catch (error) {
                console.error("Failed to remove item from cart:", error);
                alert("An error occurred while removing the item.");
            }

        }

        document.addEventListener('DOMContentLoaded', CartList);
    </script>
@endsection
