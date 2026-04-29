// Vibecoded this file because I'm lazy and cannot design good UIUX for fuck

// Shared helpers 
const DEFAULT_COLOR = '#6c63ff';

function _darkenHex(hex, amt) {
    const [r, g, b] = [1, 3, 5].map(i => parseInt(hex.slice(i, i + 2), 16));
    return '#' + [r, g, b].map(c => Math.max(0, c - amt).toString(16).padStart(2, '0')).join('');
}

// Create modal colour picker 
function selectProjectColor(hex) {
    const end = _darkenHex(hex, 30);
    document.getElementById('projColorPreview').style.background =
        `linear-gradient(135deg, ${hex} 0%, ${end} 100%)`;
    document.getElementById('projColorHidden').value = hex;
    document.querySelectorAll('.proj-swatch').forEach(btn => {
        const active = btn.dataset.color === hex;
        btn.style.borderColor = active ? '#fff' : 'transparent';
        btn.style.transform = active ? 'scale(1.18)' : 'scale(1)';
        btn.classList.toggle('proj-swatch-active', active);
    });
    document.getElementById('projColorCustom').value = hex;
}

// Edit modal colour picker 
function selectEditProjectColor(hex) {
    const end = _darkenHex(hex, 30);
    document.getElementById('editProjColorPreview').style.background =
        `linear-gradient(135deg, ${hex} 0%, ${end} 100%)`;
    document.getElementById('editProjColorHidden').value = hex;
    document.querySelectorAll('.edit-proj-swatch').forEach(btn => {
        const active = btn.dataset.color === hex;
        btn.style.borderColor = active ? '#fff' : 'transparent';
        btn.style.transform = active ? 'scale(1.18)' : 'scale(1)';
    });
    document.getElementById('editProjColorCustom').value = hex;
}

// Boot on DOM ready 
document.addEventListener('DOMContentLoaded', function () {

    // Create modal: icon upload + Cropper.js 
    const fileInput = document.getElementById('projIconFileInput');
    const dropzone = document.getElementById('projIconDropzone');
    const cropImg = document.getElementById('projIconCropImg');
    const previewImg = document.getElementById('projIconPreviewImg');
    const fallbackIcon = document.getElementById('projIconFallback');
    const base64Input = document.getElementById('projIconBase64');
    const iconLabel = document.getElementById('projIconLabel');
    const cropModalEl = document.getElementById('projIconCropModal');
    const cropModal = new bootstrap.Modal(cropModalEl);
    let cropper;

    dropzone.addEventListener('click', function (e) {
        if (!e.target.closest('button')) fileInput.click();
    });

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.style.borderColor = 'var(--primary, #6366f1)';
        dropzone.style.background = 'rgba(99,102,241,.14)';
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = 'rgba(99,102,241,.45)';
        dropzone.style.background = 'rgba(99,102,241,.06)';
    });
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.style.borderColor = 'rgba(99,102,241,.45)';
        dropzone.style.background = 'rgba(99,102,241,.06)';
        if (e.dataTransfer.files.length) openCropper(e.dataTransfer.files[0]);
    });

    fileInput.addEventListener('change', function () {
        if (this.files.length) openCropper(this.files[0]);
        this.value = '';
    });

    function openCropper(file) {
        if (!file.type.startsWith('image/')) return;
        cropImg.src = URL.createObjectURL(file);
        cropModal.show();
    }

    cropModalEl.addEventListener('shown.bs.modal', () => {
        cropper = new Cropper(cropImg, { aspectRatio: 1, viewMode: 1, autoCropArea: 1, background: false });
    });
    cropModalEl.addEventListener('hidden.bs.modal', () => {
        if (cropper) { cropper.destroy(); cropper = null; }
        cropImg.src = '';
    });

    document.getElementById('projIconCropSave').addEventListener('click', () => {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        if (canvas) {
            const b64 = canvas.toDataURL('image/jpeg');
            base64Input.value = b64;
            previewImg.src = b64;
            previewImg.classList.remove('d-none');
            fallbackIcon.classList.add('d-none');
            iconLabel.textContent = 'Icon selected ✓';
        }
        cropModal.hide();
    });
    document.getElementById('projIconCropCancel').addEventListener('click', () => cropModal.hide());

    // Reset create modal on close
    document.getElementById('createProjectModal').addEventListener('hidden.bs.modal', () => {
        base64Input.value = '';
        previewImg.src = '';
        previewImg.classList.add('d-none');
        fallbackIcon.classList.remove('d-none');
        iconLabel.textContent = 'Choose an image';
        selectProjectColor(DEFAULT_COLOR);
    });

    // Create modal: colour swatches
    document.querySelectorAll('.proj-swatch').forEach(btn => {
        btn.addEventListener('click', () => selectProjectColor(btn.dataset.color));
    });
    document.getElementById('projColorCustom').addEventListener('input', function () {
        selectProjectColor(this.value);
        document.querySelectorAll('.proj-swatch').forEach(b => {
            b.style.borderColor = 'transparent';
            b.style.transform = 'scale(1)';
            b.classList.remove('proj-swatch-active');
        });
    });

    // Edit modal 
    const editModalEl = document.getElementById('editProjectModal');

    editModalEl.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        if (!btn) return;

        document.getElementById('editProjectId').value = btn.dataset.projectId ?? '';
        document.getElementById('editProjectName').value = btn.dataset.projectName ?? '';
        document.getElementById('editProjectDescription').value = btn.dataset.projectDescription ?? '';

        selectEditProjectColor(btn.dataset.projectColor || DEFAULT_COLOR);

        const iconUrl = btn.dataset.projectIcon ?? '';
        const editPreview = document.getElementById('editProjIconPreviewImg');
        const editFallback = document.getElementById('editProjIconFallback');
        const editLabel = document.getElementById('editProjIconLabel');

        if (iconUrl) {
            editPreview.src = iconUrl;
            editPreview.classList.remove('d-none');
            editFallback.classList.add('d-none');
            editLabel.textContent = 'Current icon';
        } else {
            editPreview.src = '';
            editPreview.classList.add('d-none');
            editFallback.classList.remove('d-none');
            editLabel.textContent = 'No icon set';
        }
        document.getElementById('editProjIconBase64').value = '';
    });

    // Edit modal: new icon file input
    const editFileInput = document.getElementById('editProjIconFileInput');
    editFileInput.addEventListener('change', function () {
        if (!this.files.length) return;
        const file = this.files[0];
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = ev => {
            const img = document.getElementById('editProjIconPreviewImg');
            const ico = document.getElementById('editProjIconFallback');
            document.getElementById('editProjIconBase64').value = ev.target.result;
            img.src = ev.target.result;
            img.classList.remove('d-none');
            ico.classList.add('d-none');
            document.getElementById('editProjIconLabel').textContent = 'New icon selected ✓';
        };
        reader.readAsDataURL(file);
        this.value = '';
    });

    document.getElementById('editProjIconDropzone').addEventListener('click', function (e) {
        if (!e.target.closest('button')) editFileInput.click();
    });

    // Edit modal: colour swatches
    document.querySelectorAll('.edit-proj-swatch').forEach(btn => {
        btn.addEventListener('click', () => selectEditProjectColor(btn.dataset.color));
    });
    document.getElementById('editProjColorCustom').addEventListener('input', function () {
        selectEditProjectColor(this.value);
        document.querySelectorAll('.edit-proj-swatch').forEach(b => {
            b.style.borderColor = 'transparent';
            b.style.transform = 'scale(1)';
        });
    });

    // Reset edit modal on close
    editModalEl.addEventListener('hidden.bs.modal', () => {
        document.getElementById('editProjectForm').reset();
        document.getElementById('editProjIconBase64').value = '';
        document.getElementById('editProjIconPreviewImg').src = '';
        document.getElementById('editProjIconPreviewImg').classList.add('d-none');
        document.getElementById('editProjIconFallback').classList.remove('d-none');
        selectEditProjectColor(DEFAULT_COLOR);
    });
});