@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Pesanan</h2>

        <a href="{{ route('waiter.orders.index') }}" class="btn btn-warning mb-3">Kembali</a>

        <form id="orderForm" action="{{ route('waiter.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#menuModal">
                Pilih Menu
            </button>
            <div class="mb-3">
                <label for="menuSearch">Cari Menu:</label>
                <input type="text" id="menuSearch" class="form-control" placeholder="Ketik nama menu..." autocomplete="off">
                <ul id="menuList" class="list-group mt-2"></ul>
            </div>

            <h4>Daftar Pesanan</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>harga</th>
                        <th>aksi</th>
                    </tr>
                </thead>
                <tbody id="orderItems">
                    @foreach ($order->decoded_items as $item)
                        <tr data-id="{{ $item['id'] }}">
                            <td>{{ $item['name'] }}</td>
                            <td>
                                <input type="number" name="menus[{{ $item['id'] }}][quantity]" value="{{ $item['quantity'] }}"
                                    min="1" class="form-control qty-input">
                            </td>
                            <td>Rp.{{number_format($item['price'], 0, ',', '.') }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" id="saveOrder" class="btn btn-primary">Simpan Perubahan</button>


            <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="menuModalLabel">Pilih Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach($menus as $menu)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $menu->name }}</h5>
                                                <p class="card-text">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                                                <button type="button" class="btn btn-sm btn-primary add-menu" data-id="{{ $menu->id }}"
                                                    data-name="{{ $menu->name }}" data-price="{{ $menu->price }}">
                                                    Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let menus = @json($menus);
            let selectedMenus = @json($order->decoded_items);

            document.getElementById("menuSearch").focus();

       
            document.querySelectorAll(".remove-item").forEach(button => {
                button.addEventListener("click", function () {
                    let row = this.closest("tr");
                    let menuId = parseInt(row.getAttribute("data-id"));
                    removeMenuFromOrder(menuId);
                });
            });

            document.getElementById("menuSearch").addEventListener("input", function () {
                let keyword = this.value.toLowerCase();
                let list = document.getElementById("menuList");
                list.innerHTML = "";

                if (keyword.length > 1) {
                    let filteredMenus = menus.filter(menu => menu.name.toLowerCase().includes(keyword));
                    filteredMenus.forEach((menu) => {
                        let item = document.createElement("li");
                        item.textContent = `${menu.name} - Rp${menu.price}`;
                        item.classList.add("list-group-item");
                        item.addEventListener("click", function () {
                            addMenuToOrder(menu);
                        });
                        list.appendChild(item);
                    });
                }
            });

        
            function addMenuToOrder(menu) {
                let existingRow = document.querySelector(`#orderItems tr[data-id="${menu.id}"]`);
                if (existingRow) {
                    let qtyInput = existingRow.querySelector(".qty-input");
                    qtyInput.value = parseInt(qtyInput.value) + 1;
                    return;
                }

                let row = document.createElement("tr");
                row.setAttribute("data-id", menu.id);
                row.innerHTML = `
                    <td>${menu.name}</td>
                    <td><input type="number" name="menus[${menu.id}][quantity]" value="1" min="1" class="form-control qty-input"></td>
                    <td>Rp${menu.price.toLocaleString('id-ID')}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                `;

                row.querySelector(".remove-item").addEventListener("click", function () {
                    removeMenuFromOrder(menu.id);
                });

                row.querySelector(".qty-input").addEventListener("change", function () {
                    if (this.value < 1) this.value = 1;
                });

                document.getElementById("orderItems").appendChild(row);

                
                document.getElementById("menuSearch").value = "";
                document.getElementById("menuList").innerHTML = "";

              
                if (document.getElementById("menuModal").classList.contains("show")) {
                    const modalInstance = bootstrap.Modal.getInstance(document.getElementById("menuModal"));
                    modalInstance.hide();
                }
            }


           
            function removeMenuFromOrder(menuId) {
                let row = document.querySelector(`#orderItems tr[data-id="${menuId}"]`);
                if (row) {
                    row.remove();
                }
            }


            document.querySelectorAll(".add-menu").forEach(button => {
                button.addEventListener("click", function () {
                    addMenuToOrder({
                        id: parseInt(this.dataset.id),
                        name: this.dataset.name,
                        price: parseInt(this.dataset.price)
                    });
                });
            });


            document.getElementById("saveOrder").addEventListener("click", function () {
                let orderItems = document.querySelectorAll(".qty-input");
                let menus = [];
                let valid = true;

                orderItems.forEach(input => {
                    let row = input.closest("tr");
                    let menuId = input.name.match(/\d+/)[0];
                    let quantity = parseInt(input.value);

                    if (quantity < 1 || isNaN(quantity)) {
                        valid = false;
                        input.classList.add("is-invalid");
                    } else {
                        input.classList.remove("is-invalid");
                        if (quantity > 0) {
                            menus.push({ id: menuId, quantity: quantity });
                        }
                    }
                });

                if (!valid) {
                    alert("Jumlah pesanan tidak boleh kurang dari 1! Mohon perbaiki sebelum menyimpan.");
                    return;
                }

                let form = document.getElementById("orderForm");
                let formData = new FormData(form);
                formData.append("_method", "PUT");
                formData.append("menus", JSON.stringify(menus));

                fetch(form.action, {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Pesanan berhasil diperbarui!");
                            window.location.href = "{{ route('waiter.orders.index') }}";
                        } else {
                            alert("Terjadi kesalahan saat menyimpan pesanan.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>
@endsection