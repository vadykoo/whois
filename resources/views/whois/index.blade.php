@extends('layouts.app')

@section('title', 'WHOIS Lookup')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Domain WHOIS Search</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="domain" class="form-label">Enter domain name:</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control" id="domain"
                               placeholder="cityhost.ua">
                        <button class="btn btn-primary" type="button" id="lookupBtn">
                            Search
                        </button>
                        <div class="invalid-feedback" id="errorMessage"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="d-none" id="loadingSpinner">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <pre class="bg-light p-3 rounded d-none" id="results"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const domainInput = document.getElementById('domain');
        const lookupBtn = document.getElementById('lookupBtn');
        const results = document.getElementById('results');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorMessage = document.getElementById('errorMessage');

        lookupBtn.addEventListener('click', performSearch);
        domainInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performSearch();
        });

        function performSearch() {
            const domain = domainInput.value.trim();
            if (!domain) {
                showError('Please enter a domain name');
                return;
            }

            // Reset UI
            results.classList.add('d-none');
            errorMessage.textContent = '';
            domainInput.classList.remove('is-invalid');
            loadingSpinner.classList.remove('d-none');
            lookupBtn.disabled = true;

            fetch('/api/whois', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ domain })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                results.textContent = data;
                results.classList.remove('d-none');
            })
            .catch(error => {
                let message = 'An error occurred while fetching WHOIS information';
                if (typeof error === 'object' && error.errors) {
                    message = error.message;
                }
                showError(message);
                console.error('Error:', error);
            })
            .finally(() => {
                loadingSpinner.classList.add('d-none');
                lookupBtn.disabled = false;
            });
        }

        function showError(message) {
            // console.log('consoleShow:', message);
            errorMessage.textContent = message;
            domainInput.classList.add('is-invalid');
        }
    </script>
@endpush
