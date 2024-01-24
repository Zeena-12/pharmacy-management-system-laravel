@extends('layouts.customer-layout')

@section('customer-content')
    @if (session('error'))
        <div class="sm:px-10 lg:grid-cols-2 lg:px-20 xl:px-32">
            <div class="relative rounded-lg border border-red-300 p-4">
                <div class="text-red-600">
                    {{ session('error') }}
                </div>
            </div>
        </div>
        <br>
    @endif

<x-fail-message></x-fail-message>
<x-success-message></x-success-message>
<x-errors></x-errors>

    @php
        $totalQuantity = 0;
        foreach ($cart as $cartItem) {
            $totalQuantity += $cartItem['quantity'];
        }
    @endphp


    <div class="container mx-auto mt-10">
        <div class="flex flex-col md:flex-row shadow-md my-10">
            <div class="md:w-3/4 bg-white px-10 py-10">
                <div class="flex justify-between border-b pb-8">
                    <h1 class="font-semibold text-2xl">Shopping Cart</h1>
                    <h2 class="font-semibold text-2xl">{{ $totalQuantity }} Items</h2>

                </div>
                <div class="flex mt-10 mb-5">
                    <h3 class="font-semibold text-gray-600 text-xs uppercase w-2/5">Product Details</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5">Quantity</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5">Price</h3>
                    <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5">Total</h3>
                </div>

                @php
                    $totalPrice = 0;
                @endphp
                {{-- TODO update quantity in the session itself  --}}
                @foreach ($cart as $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['productID']);
                        $itemPrice = $product->price * $cartItem['quantity'];
                        $totalPrice += $itemPrice;
                    @endphp

                    <div class="flex items-center hover:bg-gray-100 -mx-8 px-6 py-5">
                        <div class="flex w-full md:w-2/5">
                            <div class="w-20">
                                <img class="h-24" src='{{ asset("storage/$product->image") }}' alt="{{ $product->name }}">
                            </div>
                            <div class="flex flex-col justify-between ml-4 flex-grow">
                                <span class="font-bold text-sm">{{ $product->name }}</span>
                                <span class="font-bold text-purple-500 text-xs">{{ $product->category }}</span>
                                <a href="{{ route('cart.remove', $cartItem['productID']) }}"
                                    class="font-semibold hover:text-red-500 text-gray-500 text-xs">Remove</a>
                            </div>
                        </div>
                        <div class="flex justify-center w-full md:w-1/5">
                            <button class="quantity-btn minus-btn" onclick="decrementQuantity(this)">-</button>
                            <input class="mx-2 border text-center w-12 quantity-input" type="text"
                                value="{{ $cartItem['quantity'] }}" data-product-id="{{ $cartItem['productID'] }}">
                            <button class="quantity-btn plus-btn" onclick="incrementQuantity(this)">+</button>
                        </div>
                        <span class="text-center w-full md:w-1/5 font-semibold text-sm">BD
                            {{ number_format($product->price, 2) }}</span>
                        <span class="text-center w-full md:w-1/5 font-semibold text-sm">BD
                            {{ number_format($itemPrice, 2) }}</span>
                    </div>
                @endforeach

                @if (count($cart) === 0)
                    <div class="flex items-center p-4 mb-4 text-sm w-2/4  text-red-500 rounded-lg bg-purple-50"
                        role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <span class="font-medium">&nbsp; No Products Found.</span>
                        </div>
                    </div>
                @endif

                <a href="{{ route('customer.index') }}" class="flex font-semibold text-indigo-600 text-sm mt-10">
                    <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512"></svg>
                    Continue Shopping
                </a>
            </div>

            <div id="summary" class="w-1/4 px-8 py-10">
                <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
                <div class="flex justify-between mt-10 mb-5">
                    <span class="font-semibold text-sm">{{ $totalQuantity }} Items</span>
                    <span class="font-semibold text-sm">BD {{ number_format($totalPrice, 2) }}</span>
                </div>
                <div>
                    <label class="font-medium inline-block mb-3 text-sm">Shipping</label>
                    <select class="block p-2 text-gray-600 w-full text-sm">
                        <option>Standard shipping - BD 2.00</option>
                    </select>
                </div>
                <div class="border-t mt-8">
                    <div class="flex font-semibold justify-between pt-6 pb-3 text-sm">
                        <span>Shipping Fee</span>
                        <span>BD 2.00</span>
                    </div>
                    <div class="flex font-semibold justify-between pt-3 pb-6 text-sm">
                        <span>Total cost</span>
                        <span>BD {{ number_format($totalPrice + 2, 2) }}</span>
                    </div>

                     {{-- message to refresh --}}
                     {{-- <div class="flex items-center p-1 mb-2 text-sm w-full  text-pruple-600 rounded-lg bg-purple-50"
                     role="alert">
                     <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="currentColor" viewBox="0 0 20 20">
                         <path
                             d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                     </svg>
                     <span class="sr-only">Info</span>
                     <div>
                         <span class="font-medium">&nbsp; Refresh to update total cost.</span>
                     </div> --}}


                    </div>
                    <a href="{{ route('customer.checkout') }}">
                        <button
                            class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white w-full <?php echo empty($cart) ? 'bg-gray-400 hover:bg-gray-400 cursor-not-allowed' : ''; ?>"
                            <?php echo empty($cart) ? 'disabled' : ''; ?>>Checkout</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function incrementQuantity(btn) {
            var input = btn.parentNode.querySelector('.quantity-input');
            var productId = input.getAttribute('data-product-id');
            var quantity = parseInt(input.value);

            if (quantity >= 0) {
                input.value = quantity + 1;
                updateCartQuantity(productId, quantity + 1, 'increment');
            }
        }

        function decrementQuantity(btn) {
            var input = btn.parentNode.querySelector('.quantity-input');
            var productId = input.getAttribute('data-product-id');
            var quantity = parseInt(input.value);

            if (quantity > 0) {
                input.value = quantity - 1;
                updateCartQuantity(productId, quantity - 1, 'decrement');
            }
        }

        function updateCartQuantity(productId, quantity, action) {
            var url;
            var method;

            if (action === 'increment') {
                url = '/add-to-cart/' + productId;
                method = 'POST';
            } else if (action === 'decrement') {
                url = '/cart/newDecrement/' + productId;
                method = 'POST';
            }

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the cart HTML if necessary
                        location.reload(); // Refresh the page after successful quantity update
                    } else {
                        console.error('An error occurred while updating the quantity.');
                    }
                })
                .catch(error => {
                    console.error('An error occurred while updating the quantity:', error);
                });
        }
    </script>
@endsection
