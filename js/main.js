// Main UI JavaScript for Discover Isabela Travel & Tourism Website

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Navigation Menu Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('open');
            mobileToggle.setAttribute('aria-expanded', isOpen);
        });
    }

    // 2. Background Looped Music Handler (Home Page Only - Instant Autoplay)
    const bgAudio = document.getElementById('bgAudio');
    const toggleBtn = document.getElementById('toggleBgAudioBtn');

    if (bgAudio) {
        const updateUI = (isPlaying) => {
            if (toggleBtn) {
                const labelSpan = toggleBtn.querySelector('.audio-btn-label');
                if (labelSpan) {
                    labelSpan.textContent = isPlaying ? 'Ambient Music: Playing' : 'Ambient Music: Paused';
                }
            }
        };

        const forcePlayAudio = () => {
            bgAudio.muted = false;
            bgAudio.play().then(() => {
                updateUI(true);
            }).catch((err) => {
                console.log("Autoplay waiting for initial interaction:", err);
                updateUI(false);
            });
        };

        // Immediate play attempt on load
        forcePlayAudio();

        // Autoplay fallback: trigger audio play on any mouse movement, scroll, touch or key press
        const events = ['click', 'mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'pointerdown'];
        const playOnInteraction = () => {
            if (bgAudio.paused) {
                forcePlayAudio();
            }
            events.forEach(evt => window.removeEventListener(evt, playOnInteraction));
        };

        events.forEach(evt => window.addEventListener(evt, playOnInteraction, { passive: true }));

        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (bgAudio.paused) {
                    forcePlayAudio();
                } else {
                    bgAudio.pause();
                    updateUI(false);
                }
            });
        }
    }

    // 3. Transcripts Accordion Toggle
    const transcriptToggles = document.querySelectorAll('.transcript-toggle');
    transcriptToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const content = toggle.nextElementSibling;
            if (content && content.classList.contains('transcript-content')) {
                const isOpen = content.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen);
                
                const labelSpan = toggle.querySelector('.transcript-status-label');
                if (labelSpan) {
                    labelSpan.textContent = isOpen ? 'Hide Transcript' : 'Show Transcript';
                }
                
                const iconSvg = toggle.querySelector('.transcript-icon');
                if (iconSvg) {
                    iconSvg.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            }
        });
    });

    // 4. Gallery Category Filter
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryGrid = document.getElementById('galleryGrid');

    function applyFilter(filterValue) {
        const items = document.querySelectorAll('.gallery-item');
        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (filterValue === 'all' || itemCategory === filterValue) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilter(btn.getAttribute('data-filter'));
        });
    });

    // 5. Lightbox Modal Preview for Images
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    function bindLightbox() {
        const clickableImages = document.querySelectorAll('.lightbox-trigger');
        clickableImages.forEach(img => {
            img.onclick = () => {
                if (modal && modalImg) {
                    modal.classList.add('active');
                    modalImg.src = img.getAttribute('src');
                    modalImg.alt = img.getAttribute('alt') || 'Preview image';
                }
            };
        });
    }
    bindLightbox();

    if (modal) {
        if (closeModal) {
            closeModal.addEventListener('click', () => modal.classList.remove('active'));
        }
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    }

    // 6. Drag and Drop File Upload Handler & LocalStorage Upload Store
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('mediaFile');
    const filePreview = document.getElementById('filePreview');
    const uploadForm = document.getElementById('uploadForm');

    if (dropZone && fileInput) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFilePreview(files[0]);
            }
        });

        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                updateFilePreview(fileInput.files[0]);
            }
        });

        function updateFilePreview(file) {
            if (filePreview) {
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                filePreview.innerHTML = `
                    <div style="margin-top: 1rem; color: var(--color-accent-sky); font-weight: 600; font-size: var(--text-sm);">
                        Selected File: ${file.name} (${sizeMB} MB)
                    </div>
                `;
            }
        }
    }

    // Handle Upload Submission & Store in localStorage
    if (uploadForm) {
        uploadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const file = fileInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(evt) {
                const dataUrl = evt.target.result;
                const title = document.getElementById('mediaTitle').value;
                const description = document.getElementById('mediaDescription').value;
                const category = document.getElementById('mediaCategory').value;

                let mediaType = 'images';
                if (file.type.startsWith('audio/') || ['mp3','wav','ogg'].some(ext => file.name.endsWith(ext))) {
                    mediaType = 'audio';
                } else if (file.type.startsWith('video/') || ['mp4','webm'].some(ext => file.name.endsWith(ext))) {
                    mediaType = 'videos';
                }

                const uploadItem = {
                    id: 'up_' + Date.now(),
                    title: title || file.name,
                    description: description || 'User uploaded media asset.',
                    category: category || 'destination',
                    type: mediaType,
                    dataUrl: dataUrl,
                    filename: file.name,
                    size: (file.size / (1024 * 1024)).toFixed(2) + ' MB',
                    timestamp: new Date().toLocaleDateString()
                };

                let uploads = JSON.parse(localStorage.getItem('travel_user_uploads') || '[]');
                uploads.unshift(uploadItem);
                localStorage.setItem('travel_user_uploads', JSON.stringify(uploads));

                const alert = document.getElementById('uploadAlert');
                if (alert) {
                    alert.style.display = 'block';
                    alert.className = 'alert alert-success';
                    alert.innerHTML = `Success! Media asset <strong>"${uploadItem.title}"</strong> uploaded successfully. <a href="gallery.html" style="color: var(--color-brand); font-weight: 700; text-decoration: underline;">View in Media Gallery</a> or <a href="index.html" style="color: var(--color-brand); font-weight: 700; text-decoration: underline;">View on Homepage</a>.`;
                }

                uploadForm.reset();
                if (filePreview) filePreview.innerHTML = '';
            };
            reader.readAsDataURL(file);
        });
    }

    // Load Dynamic Uploads onto Homepage Grid (index.html)
    const homepageGrid = document.getElementById('homepageCardsGrid');
    if (homepageGrid) {
        const uploads = JSON.parse(localStorage.getItem('travel_user_uploads') || '[]');
        uploads.filter(item => item.type === 'images').forEach(item => {
            const card = document.createElement('article');
            card.className = 'card';
            card.innerHTML = `
                <div class="card-media">
                    <img src="${item.dataUrl}" alt="${item.title}" class="lightbox-trigger" loading="lazy">
                </div>
                <div class="card-body">
                    <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        ${item.category.toUpperCase()}
                    </span>
                    <h3 class="card-title">${item.title}</h3>
                    <p class="card-text">${item.description}</p>
                </div>
            `;
            homepageGrid.prepend(card);
        });
        bindLightbox();
    }

    // Load Dynamic Uploads onto Media Gallery (gallery.html)
    if (galleryGrid) {
        const uploads = JSON.parse(localStorage.getItem('travel_user_uploads') || '[]');
        uploads.forEach(item => {
            const card = document.createElement('article');
            card.className = 'card gallery-item';
            card.setAttribute('data-category', item.type);

            let mediaHTML = '';
            if (item.type === 'images') {
                mediaHTML = `<img src="${item.dataUrl}" alt="${item.title}" class="lightbox-trigger" style="max-height: 170px; width: 100%; object-fit: cover;">`;
            } else if (item.type === 'audio') {
                mediaHTML = `
                    <div style="width: 100%; text-align: center;">
                        <div style="color: var(--color-accent-gold); margin-bottom: 0.5rem;">
                            <svg class="icon-svg" style="width: 2.5rem; height: 2.5rem;" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
                        </div>
                        <audio controls style="width: 100%;">
                            <source src="${item.dataUrl}">
                        </audio>
                    </div>`;
            } else if (item.type === 'videos') {
                mediaHTML = `<video controls style="width: 100%; max-height: 170px;"><source src="${item.dataUrl}"></video>`;
            }

            card.innerHTML = `
                <div class="card-media" style="padding: 0.85rem; background: var(--color-overlay); min-height: 190px; display: flex; align-items: center; justify-content: center;">
                    ${mediaHTML}
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span class="badge badge-${item.type.replace(/s$/, '')}">${item.type.toUpperCase()}</span>
                        <span style="font-size: var(--text-xs); color: var(--color-text-muted);">${item.size}</span>
                    </div>
                    <h3 class="card-title" style="font-size: var(--text-base);">${item.title}</h3>
                    <p class="card-text" style="font-size: var(--text-xs); margin-bottom: 0.5rem;">${item.description}</p>
                    <p style="font-size: var(--text-xs); color: var(--color-text-dim); margin-bottom: 0.85rem;">File: ${item.filename}</p>
                    <div style="margin-top: auto; padding-top: 0.85rem; border-top: 1px solid var(--color-border); display: flex; justify-content: flex-end;">
                        <button type="button" class="btn btn-danger delete-upload-btn" data-id="${item.id}">Delete</button>
                    </div>
                </div>
            `;
            galleryGrid.prepend(card);
        });

        document.querySelectorAll('.delete-upload-btn').forEach(btn => {
            btn.onclick = () => {
                if (confirm('Delete this uploaded file?')) {
                    const id = btn.getAttribute('data-id');
                    let uploads = JSON.parse(localStorage.getItem('travel_user_uploads') || '[]');
                    uploads = uploads.filter(u => u.id !== id);
                    localStorage.setItem('travel_user_uploads', JSON.stringify(uploads));
                    btn.closest('.gallery-item').remove();
                }
            };
        });

        bindLightbox();
    }
});
