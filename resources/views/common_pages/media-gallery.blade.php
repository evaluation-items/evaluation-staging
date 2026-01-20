@extends('layouts.app')
@section('content')
<style>
.gallery-wrapper {
    padding: 20px 0;
}

.gallery-card {
    border-radius: 10px;
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #0b3c5d, #0d6efd);
    color: #fff;
    font-weight: 600;
    font-size: 18px;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0;
}

.gallery-grid {
     margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;   /* 🔥 Adds space below gallery */
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    background: #fff;
}

.gallery-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

.gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 10px;
    font-size: 14px;
    text-align: center;
}

/* Modal */
.gallery-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.gallery-modal img {
    max-width: 90%;
    max-height: 80%;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(255,255,255,0.2);
}

.gallery-modal .close-btn {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 30px;
    color: white;
    cursor: pointer;
}

@media(max-width: 768px) {
    .gallery-item img {
        height: 180px;
    }
}
</style>

<div class="row">
    <div class="col-lg-12 gallery-wrapper">

        <h4 class="page-title text-center">Media Gallery</h4>

        <div class="container">
            <div class="card shadow gallery-card">
                <div class="card-header">
                    Photo Gallery
                </div>

                <div class="card-body">

                    <!-- Gallery Grid -->
                    <div class="gallery-grid">
                        <!-- Image 1 -->
                        <div class="gallery-item" onclick="openModal(this)">
                            <img src="{{ asset('img/gallery/picture.JPG') }}" alt="Gallery Image 1">
                            <!-- <div class="gallery-caption">Inauguration Event</div> -->
                        </div>

                        <!-- Image 2 -->
                        <div class="gallery-item" onclick="openModal(this)">
                            <img src="{{ asset('img/gallery/picture2.JPG') }}" alt="Gallery Image 2">
                            <!-- <div class="gallery-caption">Training Program</div> -->
                        </div>

                        <!-- Image 3 -->
                        <div class="gallery-item" onclick="openModal(this)">
                            <img src="{{ asset('img/gallery/picture3.JPG') }}" alt="Gallery Image 3">
                            <!-- <div class="gallery-caption">Workshop Session</div> -->
                        </div>

                        <!-- Image 4 -->
                        <div class="gallery-item" onclick="openModal(this)">
                            <img src="{{ asset('img/gallery/picture4.JPG') }}" alt="Gallery Image 4">
                            <!-- <div class="gallery-caption">Department Meeting</div> -->
                        </div>

                        <!-- Add more images as needed -->
                        <div class="gallery-item" onclick="openModal(this)">
                            <img src="{{ asset('img/gallery/picture5.JPG') }}" alt="Gallery Image 4">
                            <!-- <div class="gallery-caption">Department Meeting</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="gallery-modal" id="galleryModal">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="Full Image">
</div>

<script>
function openModal(element) {
    var img = element.querySelector('img');
    var modal = document.getElementById('galleryModal');
    var modalImg = document.getElementById('modalImage');

    modal.style.display = 'flex';
    modalImg.src = img.src;
}

function closeModal() {
    document.getElementById('galleryModal').style.display = 'none';
}

// Close modal when clicking outside image
window.onclick = function(event) {
    var modal = document.getElementById('galleryModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

@endsection
