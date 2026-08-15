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
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');

            galleryItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                if (filterValue === 'all' || itemCategory === filterValue) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // 5. Lightbox Modal Preview for Images
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');
    const clickableImages = document.querySelectorAll('.lightbox-trigger');

    if (modal && modalImg) {
        clickableImages.forEach(img => {
            img.addEventListener('click', () => {
                modal.classList.add('active');
                modalImg.src = img.getAttribute('src');
                modalImg.alt = img.getAttribute('alt') || 'Preview image';
            });
        });

        if (closeModal) {
            closeModal.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    }

    // 6. Drag and Drop File Upload Handler
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('mediaFile');
    const filePreview = document.getElementById('filePreview');

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
});
