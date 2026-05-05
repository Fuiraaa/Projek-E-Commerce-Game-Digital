<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="font-heading text-3xl font-bold text-white neon-text">Add New Game</h1>
            <p class="text-gray-400 mt-1">Submit a new game for review</p>
        </div>

        <form id="gameForm" action="{{ route('developer.games.store') }}" method="POST" enctype="multipart/form-data" class="glass-card p-6">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors" placeholder="Enter game title" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors resize-none" placeholder="Describe your game" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Price (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors" placeholder="0" required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-neon-blue/20 file:text-neon-cyan file:cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG. Max 2MB.</p>
                    @error('cover_image')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gameplay Trailer (Video)</label>
                    <input type="file" name="trailer_video" accept="video/mp4,video/webm" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-neon-blue/20 file:text-neon-cyan file:cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">MP4, WebM. Max 50MB. Format horizontal.</p>
                    @error('trailer_video')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gallery Photos (Max 5)</label>
                    <input type="file" name="gallery_photos[]" multiple accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-neon-blue/20 file:text-neon-cyan file:cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG. Max 5 files. Max 2MB per file. Format horizontal.</p>
                    @error('gallery_photos')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    @error('gallery_photos.*')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Game File (.zip)</label>
                    <input type="file" id="gameFileInput" name="game_file" accept=".zip" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-neon-blue/20 file:text-neon-cyan file:cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">Upload a .zip file containing your game folder + .exe file. Max 200MB.</p>
                    @error('game_file')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    <div id="uploadProgressContainer" class="hidden mt-3">
                        <div class="flex items-center justify-between mb-1">
                            <span id="uploadFileName" class="text-xs text-gray-400 truncate max-w-[200px]"></span>
                            <span id="uploadPercent" class="text-xs text-neon-cyan">0%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                            <div id="uploadProgressBar" class="h-full rounded-full bg-gradient-to-r from-neon-blue to-neon-violet transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="uploadStatus" class="text-xs text-gray-500 mt-1">Preparing upload...</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" id="submitBtn" class="btn-primary px-6 py-3 rounded-xl text-white font-medium flex items-center gap-2">
                    <span id="btnText">Submit Game</span>
                    <svg id="btnSpinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
                <a href="{{ route('developer.games.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const form = document.getElementById('gameForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const progressContainer = document.getElementById('uploadProgressContainer');
        const progressBar = document.getElementById('uploadProgressBar');
        const uploadPercent = document.getElementById('uploadPercent');
        const uploadFileName = document.getElementById('uploadFileName');
        const uploadStatus = document.getElementById('uploadStatus');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const fileInput = document.getElementById('gameFileInput');
            const hasFile = fileInput.files.length > 0;

            if (hasFile) {
                const file = fileInput.files[0];
                uploadFileName.textContent = file.name;
                progressContainer.classList.remove('hidden');
                submitBtn.disabled = true;
                btnText.textContent = 'Uploading...';
                btnSpinner.classList.remove('hidden');

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + '%';
                        uploadPercent.textContent = percent + '%';

                        if (percent < 100) {
                            uploadStatus.textContent = 'Uploading... ' + (e.loaded / 1024 / 1024).toFixed(1) + ' MB / ' + (e.total / 1024 / 1024).toFixed(1) + ' MB';
                        } else {
                            uploadStatus.textContent = 'Upload complete! Saving game...';
                        }
                    }
                });

                xhr.addEventListener('load', function() {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect && response.success) {
                            window.location.href = response.redirect + '?success=' + encodeURIComponent(response.success);
                            return;
                        }
                    } catch(e) {}
                    window.location.href = xhr.getResponseHeader('Location') || '{{ route("developer.games.index") }}';
                });

                xhr.addEventListener('error', function() {
                    uploadStatus.textContent = 'Upload failed. Please try again.';
                    uploadStatus.classList.add('text-red-400');
                    submitBtn.disabled = false;
                    btnText.textContent = 'Submit Game';
                    btnSpinner.classList.add('hidden');
                });

                xhr.open('POST', '{{ route("developer.games.store") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.send(formData);
            } else {
                this.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>
