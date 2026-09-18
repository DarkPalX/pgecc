export function initModuleScripts() {
    const fileInput = document.getElementById('fileInput');
    const dropzone = document.getElementById('dropzone');
    const fileIcon = document.getElementById('fileIcon');
    const mainText = document.getElementById('mainText');
    const subText = document.getElementById('subText');
    const removeContainer = document.getElementById('removeButtonContainer');
    const btnRemove = document.getElementById('btnRemove');

    if (!fileInput || !dropzone || !fileIcon || !mainText || !subText || !removeContainer || !btnRemove) {
        return;
    }

    // Map your module color to explicit full Tailwind classes
    const colorMap = {
        default: {
            text: 'text-gray-500',
            border: 'border-gray-500',
            bg: 'bg-gray-50/10'
        },
        carenderia: {
            text: 'text-amber-500',
            border: 'border-amber-500',
            bg: 'bg-amber-50/10'
        },
        loan: {
            text: 'text-emerald-500',
            border: 'border-emerald-500',
            bg: 'bg-emerald-50/10'
        },
        grocery: {
            text: 'text-blue-500',
            border: 'border-blue-500',
            bg: 'bg-blue-50/10'
        },
        payments: {
            text: 'text-rose-500',
            border: 'border-rose-500',
            bg: 'bg-rose-50/10'
        },
    };
    const routePath = window.location.pathname;
    const pathSegments = routePath.split('/');
    const activeColors = colorMap[pathSegments[1]] || colorMap['default'];

    // Handle File Selection
    fileInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            const extension = file.name.split('.').pop().toUpperCase();

            // 1. Swap the texts to display name and extension metadata
            mainText.textContent = file.name;
            subText.textContent = `File Extension: .${extension} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            mainText.classList.add('text-gray-600');
            subText.classList.add('text-gray-400');

            // 2. Change icon visual state to active color
            fileIcon.classList.remove('text-gray-300');
            fileIcon.classList.add(activeColors.text);

            // 3. Make container keep its active hover borders permanently while loaded
            dropzone.classList.remove('border-gray-300');
            dropzone.classList.add(activeColors.border, activeColors.bg);

            // 4. Show the remove button component
            removeContainer.classList.remove('hidden');

            // 5. CRITICAL: Disable file input pointer events so clicks pass directly down to our remove button
            fileInput.classList.add('pointer-events-none');
        }
    });

    // Handle File Removal
    btnRemove.addEventListener('click', function (e) {
        // Prevent click bubbling up and triggering the file selector window again
        e.preventDefault();
        e.stopPropagation();

        // 1. Reset input value completely
        fileInput.value = '';

        // 2. Re-enable interactive pointer capture on file input
        fileInput.classList.remove('pointer-events-none');

        // 3. Restore default template texts
        mainText.textContent = 'Click to select or drag document here';
        mainText.classList.add('text-gray-400', 'group-hover:text-gray-600');
        mainText.classList.remove('text-gray-600');


        subText.textContent = 'XLSX, XLS, or CSV format up to 10MB';
        subText.classList.add('text-gray-300', 'group-hover:text-gray-400');        
        subText.classList.remove('text-gray-400');


        // 4. Revert state colors and layouts back to normal
        // fileIcon.classList.remove(`text-${fileModuleUploadColor}-500`);
        fileIcon.classList.remove(`${activeColors.text}`);
        fileIcon.classList.add('text-gray-300');
        // dropzone.classList.remove(`border-${fileModuleUploadColor}-500`, `bg-${fileModuleUploadColor}-50/10`);
        dropzone.classList.remove(`${activeColors.border}`, `${activeColors.bg}`);
        dropzone.classList.add('border-gray-300');

        // 5. Hide the remove element button away again
        removeContainer.classList.add('hidden');
    });
}
