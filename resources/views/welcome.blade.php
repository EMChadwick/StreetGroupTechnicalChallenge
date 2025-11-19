<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Tenant Upload Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="flex flex-col items-center text-center gap-12 text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg">
                    <h1 class="text-2xl font-bold text-center">Tenant Upload Portal</h1>
                    <div class="flex flex-col items-center w-[50%]">
                        <form id="csv-form" action="{{ route('tenant-upload') }}" method="POST" enctype="multipart/form-data" class="flex w-full items-center gap-0">
                            @csrf
                            <label for="csv-file" class="flex-[2] h-12 flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 cursor-pointer">
                                <span id="csv-file-label" class="text-gray-900 dark:text-gray-100 text-sm truncate">
                                    Input Tenant CSV
                                </span>
                                <input type="file" id="csv-file" name="csv_file" accept=".csv" class="hidden">
                            </label>
                            <div class="flex-[1] h-12">
                                <button
                                    id="upload-btn"
                                    type="submit"
                                    disabled
                                    class="w-full h-full rounded-r-lg bg-white dark:bg-gray-600 border border-l-0 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 disabled:bg-gray-200 dark:disabled:bg-gray-700 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-500 flex items-center justify-center"
                                >
                                    Upload CSV
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- errors -->
                    @if ($errors->any())
                        <ul style="color: red;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <!-- Processed Homeowner records -->
                    <div id="server-response" class="mt-4 text-sm"></div>
                </div>
            </main>
        </div>
    </body>
<script>
    const fileInput = document.getElementById('csv-file');
    const submitBtn = document.getElementById('upload-btn');
    const form = document.getElementById('csv-form');
    const responseDiv = document.getElementById('server-response');
    const csvFileLabel = document.getElementById('csv-file-label');

    fileInput.addEventListener('change', () => {
        submitBtn.disabled = !fileInput.files.length;
        csvFileLabel.innerHTML = fileInput.files[0] 
            ? fileInput.files[0].name 
            : "Tenant Upload Portal";
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        responseDiv.textContent = 'Uploading';
        responseDiv.className = 'mt-4 text-sm text-gray-900 dark:text-gray-100';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                let errorMessages = '';
                if (data.errors) {
                    errorMessages = Object.values(data.errors).flat().join('\n');
                } else if (data.message) {
                    errorMessages = data.message;
                }
                responseDiv.textContent = errorMessages;
                responseDiv.classList.add('text-red-500', 'dark:text-red-400');
            } else {
                // show the JSON data with some basic formatting
                responseDiv.innerHTML = '';
                data.data.forEach(row => {
                    const rowDiv = document.createElement('div');
                    for (const [key, value] of Object.entries(row)) {
                        rowDiv.innerHTML += `<strong>${key}:</strong> ${value}&nbsp;&nbsp;`;
                    }
                    responseDiv.appendChild(rowDiv);
                });
            }
        } catch (err) {
            responseDiv.textContent = 'Error uploading file: ' + err.message;
            responseDiv.classList.add('text-red-500', 'dark:text-red-400');
        }
    });
</script>
</html>
