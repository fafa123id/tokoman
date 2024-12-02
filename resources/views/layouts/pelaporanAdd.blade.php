<?php
$line = 1;
?>
</style>
<div class="flex flex-col items-center justify-center mt-10">
    <h1 class="text-3xl text-black dark:text-white font-semibold mb-4">Formulir Penjualan</h1>
    <div class="flex items-center justify-center space-x-4 mb-2 ">
        <form id="addForm" action="{{route('pelaporan.postData')}}" method="post" id="reportingForm">
            @csrf
            <input type="hidden" name="line" id="line" value="1">
            <div>
                <div id="inputLines" class="flex flex-col">
                    <div class="md:ml-40 grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="">
                            <label for="reportDate1"
                                class="block text-sm font-medium text-black dark:text-white">Tanggal:</label>
                            <input type="date" id="reportDate1" name="reportDate1"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-3000 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required value="{{ now()->toDateString() }}" readonly>
                        </div>
                        <div class="">
                            <label for="itemName1" class="block text-sm font-medium text-black dark:text-white">Nama
                                Barang:</label>
                            <select id="itemName1" name="itemName1"
                                class="mt-1 p-2 pr-8 w-full rounded-md truncate border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                @foreach($options as $options)
                                    <option value="{{ $options->id }}" class="truncate"
                                        data-price="{{ $options->harga_jual }}">
                                        {{ $options->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="itemQuantity1"
                                class="block text-sm font-medium text-black dark:text-white">Jumlah Barang:</label>
                            <input type="number" id="itemQuantity1" name="itemQuantity1" min="1"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                        </div>
                        <div>
                            <label for="itemPrice1" class="block text-sm font-medium text-black dark:text-white">Harga
                                Barang:</label>
                            <input type="number" id="itemPrice1" name="itemPrice1" min="0.01" step="0.01"
                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                readonly required>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-2">
                    <div class="mt-4">
                        <button type="button" id="addLineBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Tambah
                            penjualan lain</button>
                    </div>
                    <div class="mt-4">
                        <button type="button" onclick="validasiForm()"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Tambah</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript to handle adding new input lines -->
    <script>
        let line = 1;

        function handleItemNameChange(line) {
            const itemNameSelect = document.getElementById(`itemName${line}`);
            itemNameSelect.addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var itemPriceInput = document.getElementById(`itemPrice${line}`);
                itemPriceInput.value = selectedOption.getAttribute('data-price');
            });
        }

        function setInitialItemPrice(line) {
            const itemNameSelect = document.getElementById(`itemName${line}`);
            const selectedOption = itemNameSelect.options[itemNameSelect.selectedIndex];
            const itemPriceInput = document.getElementById(`itemPrice${line}`);
            itemPriceInput.value = selectedOption.getAttribute('data-price');
        }

        function generateOptions() {
            let optionsHTML =
                `@foreach($options2 as $option2)
                    <option class="truncate" value="{{ $option2->id }}" data-price="{{ $option2->harga_jual }}">{{ $option2->nama_barang }}</option>
                @endforeach
            `
            return optionsHTML;
        }

        document.addEventListener('DOMContentLoaded', function () {
            setInitialItemPrice(line); // Set initial price for the first line
            handleItemNameChange(line); // Attach event listener to the first line
        });

        addLineBtn.addEventListener('click', () => {
            line++;
            const hitungLine = document.getElementById('line');
            hitungLine.value = line;
            const inputLinesDiv = document.getElementById('inputLines');
            const newLineDiv = document.createElement('div');
            newLineDiv.className = 'md:ml-40 grid grid-cols-2 md:grid-cols-5 gap-4 mt-4';
            newLineDiv.innerHTML = `
            <div>
                <label for="reportDate${line}" class="block text-sm font-medium text-black dark:text-white">Tanggal: </label>
                <input type="date" id="reportDate${line}" name="reportDate${line}" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required min="{{ now()->subWeek()->toDateString() }}" max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
            </div>
            <div>
                <label for="itemName${line}" class="block text-sm font-medium text-black dark:text-white">Nama Barang:</label>
                <select id="itemName${line}" name="itemName${line}" class="mt-1 p-2 pr-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 truncate" required>
                    ${generateOptions()}
                </select>
            </div>
            <div>
                <label for="itemQuantity${line}" class="block text-sm font-medium text-black dark:text-white">Jumlah Barang:</label>
                <input type="number" id="itemQuantity${line}" name="itemQuantity${line}" min="1" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>
            <div>
                <label for="itemPrice${line}" class="block text-sm font-medium text-black dark:text-white">Harga Barang:</label>
                <input type="number" id="itemPrice${line}" name="itemPrice${line}" min="0.01" step="0.01" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly required>
            </div>
            <div>
                <label class="block text-red-700 text-sm font-medium">Hapus</label>
                    <button type="button" class="text-red-500 hover:text-red-700 focus:outline-none inline-block px-3 py-2 mt-1 bg-gray-200 rounded-md shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
             
            </div>
        `;
            const deleteBtn = newLineDiv.querySelector('button');
            deleteBtn.addEventListener('click', () => {
                line--;
                const hitungLine = document.getElementById('line');
                hitungLine.value = line;
                inputLinesDiv.removeChild(newLineDiv);
            });
            inputLinesDiv.appendChild(newLineDiv);
            setInitialItemPrice(line); // Set initial price for the new line
            handleItemNameChange(line); // Attach event listener to the new line
        });

        // Call the handleItemNameChange function for the initial line
        handleItemNameChange(line);

        // Initial event listener for the first line
        document.getElementById(`itemName1`).addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var itemPriceInput = document.getElementById(`itemPrice1`);
            itemPriceInput.value = selectedOption.getAttribute('data-price');
        });
    </script>

</div>
</div>
@include('modalCustom.themodal', ['message' => 'Yakin Mau Tambahkan Data?', 'form' => 'addForm'])