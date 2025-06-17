<x-app-layout>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
        }

        h3 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a202c;
            text-align: center;
        }

        .category-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .category-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .category-card:hover {
            transform: scale(1.02);
        }

        .category-title {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }

        .action-buttons button {
            border: none;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #bd2130;
        }

        @media (max-width: 600px) {
            h3 {
                font-size: 1.5rem;
                text-align: center;
            }

            .action-buttons {
                justify-content: center;
            }
        }
    </style>
</head>

<section>
    <h3>All Categories</h3>

    <div class="category-container">
        @foreach($categories as $category)
            <div class="category-card">
                <div class="category-title">{{ $category->name }}</div>
                <div class="action-buttons">
                    <form method="post" class="edit-form" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                           @csrf
                           <button class="btn-warning edit-category" type="button">
                           <i class="fas fa-edit"></i>
                           </button>
                  </form>

                    <form action="{{ route('category.destroy', $category->id) }}" method="post" class="delete-form">
                           @csrf
                           @method('DELETE')
                           <button class="btn-danger fake-delete-btn" type="button">
                           <i class="fas fa-trash"></i>
                           </button>
                  </form>

                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    document.querySelectorAll('.fake-delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('.delete-form');

        Swal.fire({
            title: "Are you sure?",
            text: "This category will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.edit-category').forEach(button => {
    button.addEventListener('click', async function () {
        const form = this.closest('.edit-form');
        const categoryId = form.getAttribute('data-id');
        const oldName = form.getAttribute('data-name');

        const { value: newName } = await Swal.fire({
            title: "Edit Category",
            input: "text",
            inputLabel: "Category Name",
            inputValue: oldName,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value.trim()) {
                    return "Category name cannot be empty!";
                }
            }
        });

        if (newName && newName !== oldName) {
            try {
                const response = await fetch(`/categories/${categoryId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ name: newName })
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire("Updated!", result.message || "Category updated successfully!", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Error", result.message || "Update failed!", "error");
                }

            } catch (error) {
                Swal.fire("Error", "Something went wrong!", "error");
            }
        }
    });
});
</script>
</x-app-layout>
