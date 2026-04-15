<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sales') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="posSystem()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Errors -->
                <div class="col-span-full">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error!</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Left Column: Product List -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <div class="flex sm:flex-row flex-col justify-between items-center mb-6 gap-4">
                <h2 class="text-2xl font-bold text-gray-700">Products</h2>
                <input type="text" x-model="searchQuery" placeholder="Search by name or category..." 
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="border border-gray-200 rounded-xl p-4 transition bg-white flex flex-col justify-between relative overflow-hidden group">
                        
                        <!-- Low Stock Indicator -->
                        <div x-show="product.stock <= product.min_stock && product.stock > 0" class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-2 py-1 rounded-bl-lg">Low Stock</div>
                        <div x-show="product.stock <= 0" class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg">Out of Stock</div>

                        <div class="mb-2">
                            <h3 class="font-bold text-lg text-gray-800 leading-tight line-clamp-2" x-text="product.name" title="product.name"></h3>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mt-1" x-text="product.category"></p>
                        </div>
                        <div class="mt-4 flex flex-col space-y-2">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xl font-extrabold text-blue-600" x-text="formatCurrency(product.sell_price) + ' / ' + product.unit"></span>
                                <span class="text-sm font-medium" :class="product.stock <= 0 ? 'text-red-500' : 'text-gray-600'">
                                    Stock: <span x-text="product.stock"></span> <span class="text-xs" x-text="product.unit"></span>
                                </span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 mt-auto">
                                <!-- Retail Quick Add Button -->
                                <button @click="addToCart(product, 'retail')" 
                                        class="flex-1 bg-blue-50 border border-blue-200 text-blue-700 font-bold py-2 px-2 rounded-lg text-sm hover:bg-blue-100 transition flex flex-col items-center justify-center text-center leading-tight">
                                    <span>Add Retail</span>
                                    <span class="font-normal text-xs mt-0.5 text-blue-600" x-text="'1 ' + product.unit"></span>
                                </button>

                                <!-- Bulk Quick Add Button -->
                                <button x-show="product.conversion_factor > 1 && product.bulk_unit" 
                                        @click="addToCart(product, 'bulk')" 
                                        class="flex-1 bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold py-2 px-2 rounded-lg text-sm hover:bg-indigo-100 transition flex flex-col items-center justify-center text-center leading-tight">
                                    <span>Add Bulk</span>
                                    <span class="font-normal text-xs mt-0.5 text-indigo-600 truncate w-full px-1" x-text="'1 ' + product.bulk_unit"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="filteredProducts.length === 0" class="col-span-full py-16 flex flex-col items-center justify-center text-gray-500 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 mb-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Welcome to POS</h3>
                    <p class="text-sm">Search for a product or add inventory to get started.</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Cart / Invoice Summary -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col h-[calc(100vh-8rem)] sticky top-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-4 pb-4 border-b border-gray-200">Current Order</h2>
            
            <!-- Cart Items -->
            <div class="flex-grow overflow-y-auto mb-4 pr-2 space-y-3">
                <template x-for="(item, index) in cart" :key="item.unique_id">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex justify-between items-center">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm" x-text="item.name"></h4>
                            <div class="flex items-center space-x-2 mt-2">
                                <button type="button" @click="decreaseQuantity(index)" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-100 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">-</button>
                                <input type="number" step="0.01" x-model.number="item.quantity" @change="updateQuantity(index)" class="w-16 h-8 text-center border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                                <span class="text-xs text-gray-500 font-bold w-6" x-text="item.unit"></span>
                                <button type="button" @click="increaseQuantity(index)" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-100 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">+</button>
                            </div>
                        </div>
                        <div class="text-right ml-4 flex flex-col items-end">
                            <span class="font-bold text-gray-800 block" x-text="formatCurrency(item.subtotal)"></span>
                            <button type="button" @click="removeFromCart(index)" class="text-red-500 text-xs mt-2 hover:underline focus:outline-none">Remove</button>
                        </div>
                    </div>
                </template>
                
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400 space-y-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p>Click on products to add</p>
                </div>
            </div>

            <!-- Total and Payment -->
            <div class="border-t border-gray-200 pt-4 mt-auto">
                <div class="flex justify-between items-center mb-2 text-gray-600">
                    <span>Total Items:</span>
                    <span class="font-bold" x-text="totalItems"></span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xl font-bold text-gray-800">Total Price:</span>
                    <span class="text-2xl font-black text-blue-600" x-text="formatCurrency(totalPrice)"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Cash Received</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-500 font-bold text-lg">Rp</span>
                        <input type="number" x-model.number="cashReceived" class="pl-12 shadow-sm border border-gray-300 rounded-lg w-full py-3 pr-4 text-gray-700 font-bold text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0" min="0" step="1">
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-6 p-3 rounded-lg" :class="cashReceived && cashReturn >= 0 ? 'bg-green-50 border border-green-200' : (cashReceived ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200')">
                    <span class="text-gray-700 font-medium text-lg">Change:</span>
                    <span class="text-2xl font-bold" 
                          :class="cashReturn >= 0 ? 'text-green-600' : 'text-red-600'" 
                          x-text="flashChange()"></span>
                </div>

                <!-- Submit Form to Backend -->
                <form method="POST" action="{{ route('transactions.store') }}" id="checkout-form">
                    @csrf
                    <input type="hidden" name="cash_received" :value="cashReceived">
                    
                    <template x-for="(item, index) in cart">
                        <div>
                            <input type="hidden" :name="`items[${index}][product_id]`" :value="item.product_id">
                            <input type="hidden" :name="`items[${index}][quantity]`" :value="item.quantity">
                            <input type="hidden" :name="`items[${index}][price]`" :value="item.price">
                            <input type="hidden" :name="`items[${index}][subtotal]`" :value="item.subtotal">
                            <input type="hidden" :name="`items[${index}][sale_type]`" :value="item.sale_type">
                        </div>
                    </template>

                    <button type="submit" 
                            :disabled="cart.length === 0 || cashReturn < 0 || !cashReceived"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-xl shadow focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-xl uppercase tracking-wide">
                        Pay Order
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js Component Script -->
    <script>
        function posSystem() {
            return {
                products: @json($products),
                searchQuery: '',
                cart: [],
                cashReceived: '',
                
                get filteredProducts() {
                    if (this.searchQuery === '') {
                        return this.products;
                    }
                    const lowerCaseQuery = this.searchQuery.toLowerCase();
                    return this.products.filter(p => 
                        p.name.toLowerCase().includes(lowerCaseQuery) || 
                        p.category.toLowerCase().includes(lowerCaseQuery)
                    );
                },

                addToCart(product, type = 'retail') {
                    if (product.stock <= 0) {
                        alert('Product is out of stock!');
                        return;
                    }
                    
                    let salePrice = type === 'bulk' ? product.bulk_price : product.sell_price;
                    let displayUnit = type === 'bulk' ? product.bulk_unit : product.unit;
                    let uniqueId = product.id + '-' + type;
                    let factor = type === 'bulk' ? parseFloat(product.conversion_factor) : 1;
                    
                    let availableMaxQty = Math.floor(product.stock / factor);
                    
                    if (availableMaxQty <= 0) {
                        alert('Not enough stock for this unit!');
                        return;
                    }
                    
                    const existingItem = this.cart.find(i => i.unique_id === uniqueId);
                    if (existingItem) {
                        if (existingItem.quantity >= existingItem.max_stock) {
                            alert('Cannot add more than available stock!');
                            return;
                        }
                        existingItem.quantity++;
                        existingItem.subtotal = existingItem.quantity * existingItem.price;
                    } else {
                        this.cart.push({
                            unique_id: uniqueId,
                            product_id: product.id,
                            name: product.name + (type === 'bulk' ? ' (' + product.bulk_unit + ')' : ''),
                            price: salePrice,
                            quantity: 1,
                            unit: displayUnit,
                            sale_type: type,
                            subtotal: parseFloat(salePrice),
                            max_stock: availableMaxQty
                        });
                    }
                },

                increaseQuantity(index) {
                    const item = this.cart[index];
                    if (item.quantity >= item.max_stock) {
                        alert('Cannot add more than available stock!');
                        return;
                    }
                    item.quantity++;
                    item.subtotal = item.quantity * parseFloat(item.price);
                },

                decreaseQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                        this.cart[index].subtotal = this.cart[index].quantity * parseFloat(this.cart[index].price);
                    } else {
                        this.removeFromCart(index);
                    }
                },

                updateQuantity(index) {
                    let item = this.cart[index];
                    if (item.quantity > item.max_stock) {
                        alert('Cannot add more than available stock!');
                        item.quantity = item.max_stock;
                    }
                    if (item.quantity <= 0) {
                        this.removeFromCart(index);
                        return;
                    }
                    item.subtotal = item.quantity * parseFloat(item.price);
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                get totalItems() {
                    return this.cart.reduce((total, item) => total + item.quantity, 0);
                },

                get totalPrice() {
                    return this.cart.reduce((total, item) => total + item.subtotal, 0);
                },

                get cashReturn() {
                    if (!this.cashReceived && this.cashReceived !== 0) return -this.totalPrice;
                    return parseFloat(this.cashReceived) - this.totalPrice;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                },

                flashChange() {
                    if (!this.cashReceived) return this.formatCurrency(0);
                    return this.formatCurrency(this.cashReturn);
                }
            }
        }
    </script>
            </div>
        </div>
    </div>
</x-app-layout>
