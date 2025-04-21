@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Buat Pesanan</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-warning mb-3">Kembali</a>

        <form id="orderForm">
            @csrf
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="orderItems"></tbody>
            </table>

            <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
        </form>
    </div>

    
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
                                        <button class="btn btn-sm btn-primary add-menu" 
                                                data-id="{{ $menu->id }}" 
                                                data-name="{{ $menu->name }}" 
                                                data-price="{{ $menu->price }}">
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

    <script>
       document.addEventListener("DOMContentLoaded", function () {
    let menus = @json($menus);
    let menuSearch = document.getElementById("menuSearch");
    let selectedMenus = [];

    menuSearch.focus();

    menuSearch.addEventListener("input", function () {
        let keyword = this.value.toLowerCase();
        let list = document.getElementById("menuList");
        list.innerHTML = "";

        if (keyword.length > 1) {
            let filteredMenus = menus.filter(menu => menu.name.toLowerCase().includes(keyword));
            filteredMenus.forEach((menu) => {
                let item = document.createElement("li");
                item.textContent = `${menu.name} - Rp${Math.floor(menu.price).toLocaleString('id-ID')}`;
                item.classList.add("list-group-item");
                item.addEventListener("click", function () {
                    addMenuToOrder(menu);
                });
                list.appendChild(item);
            });
        }
    });

    function addMenuToOrder(menu) {
        if (selectedMenus.find(m => m.id === menu.id)) return;

        selectedMenus.push({ id: menu.id, name: menu.name, quantity: 1 });

        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${menu.name}</td>
            <td><input type="number" name="menus[${menu.id}][quantity]" value="1" min="1" class="form-control qty-input"></td>
            <td>Rp${Math.floor(menu.price).toLocaleString('id-ID')}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
        `;

        let qtyInput = row.querySelector(".qty-input");
        let removeButton = row.querySelector(".remove-item");

        qtyInput.addEventListener("change", function () {
            let menuIndex = selectedMenus.findIndex(m => m.id === menu.id);
            selectedMenus[menuIndex].quantity = parseInt(this.value);
        });

        removeButton.addEventListener("click", function () {
            selectedMenus = selectedMenus.filter(m => m.id !== menu.id);
            row.remove();
        });

        document.getElementById("orderItems").appendChild(row);
        menuSearch.value = "";
        document.getElementById("menuList").innerHTML = "";
    }

    
    document.querySelectorAll('.add-menu').forEach(button => {
        button.addEventListener('click', function() {
            addMenuToOrder({
                id: this.dataset.id,
                name: this.dataset.name,
                price: parseFloat(this.dataset.price)
            });

            
            const modalElement = document.getElementById('menuModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();  
        });
    });

    function submitOrder() {
        let formData = {
            _token: "{{ csrf_token() }}",
            menus: selectedMenus.map(m => ({ id: m.id, quantity: m.quantity }))
        };

        fetch("{{ route('waiter.orders.store') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            window.location.href = "{{ route('waiter.orders.index') }}";
        })
        .catch(error => console.error("Error:", error));
    }

    document.getElementById("orderForm").addEventListener("submit", function (e) {
        e.preventDefault();
        submitOrder();
    });
});

    </script>
@endsection
