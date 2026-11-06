/**
 * ESFAST ADMIN - CATEGORIES & BRANDS MANAGEMENT (VANILLA JS)
 * Full CRUD for Categories and Brands in LocalStorage
 */

let editingCatId = null;
let editingBrandId = null;

function initAdminCategories() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    renderCategoriesTable();
    renderBrandsTable();
}

function renderCategoriesTable() {
    const categories = getCategories();
    const tbody = document.getElementById('adminCategoriesTableBody');
    if (!tbody) return;

    tbody.innerHTML = categories.map(c => `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px 16px; font-weight: 700; color: #0052cc;">#${c.id}</td>
            <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;">${c.name}</td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #64748b;">${c.slug}</td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #475569;">${c.description}</td>
            <td style="padding: 12px 16px;">
                <button type="button" onclick="toggleCatStatus(${c.id})" style="border: none; background: ${c.isActive ? '#ecfdf5' : '#fef2f2'}; color: ${c.isActive ? '#047857' : '#b91c1c'}; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                    ${c.isActive ? 'Hiển thị' : 'Ẩn'}
                </button>
            </td>
            <td style="padding: 12px 16px; text-align: right;">
                <button type="button" onclick="openEditCatModal(${c.id})" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #0052cc; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" onclick="deleteCategory(${c.id})" style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function renderBrandsTable() {
    const brands = getBrands();
    const tbody = document.getElementById('adminBrandsTableBody');
    if (!tbody) return;

    tbody.innerHTML = brands.map(b => `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px 16px; font-weight: 700; color: #0052cc;">#${b.id}</td>
            <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;">${b.name}</td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #64748b;">${b.slug}</td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #475569;">${b.description || 'Thương hiệu đối tác'}</td>
            <td style="padding: 12px 16px;">
                <span style="background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;">Hoạt động</span>
            </td>
            <td style="padding: 12px 16px; text-align: right;">
                <button type="button" onclick="deleteBrand(${b.id})" style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openAddCategoryModal() {
    editingCatId = null;
    document.getElementById('catModalTitle').textContent = 'Thêm Mới Danh Mục Xe';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModal').style.display = 'flex';
}

function openEditCatModal(id) {
    editingCatId = id;
    const categories = getCategories();
    const cat = categories.find(c => c.id === Number(id));
    if (!cat) return;

    document.getElementById('catModalTitle').textContent = `Chỉnh Sửa Danh Mục: ${cat.name}`;
    const form = document.getElementById('categoryForm');
    form.name.value = cat.name;
    form.description.value = cat.description;
    form.icon.value = cat.icon || 'fa-motorcycle';
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeCatModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

function handleSaveCategory(e) {
    e.preventDefault();
    const form = e.target;
    const name = form.name.value.trim();
    const description = form.description.value.trim();
    const icon = form.icon.value.trim();

    const categories = getCategories();

    if (editingCatId) {
        const idx = categories.findIndex(c => c.id === editingCatId);
        if (idx > -1) {
            categories[idx].name = name;
            categories[idx].slug = name.toLowerCase().replace(/\s+/g, '-');
            categories[idx].description = description;
            categories[idx].icon = icon;
            saveCategories(categories);
            showToast('Cập nhật danh mục thành công!', 'success');
        }
    } else {
        const newId = categories.length > 0 ? Math.max(...categories.map(c => c.id)) + 1 : 1;
        categories.push({
            id: newId,
            name: name,
            slug: name.toLowerCase().replace(/\s+/g, '-'),
            description: description,
            icon: icon,
            isActive: true
        });
        saveCategories(categories);
        showToast('Thêm danh mục mới thành công!', 'success');
    }

    closeCatModal();
    renderCategoriesTable();
}

function deleteCategory(id) {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        let categories = getCategories();
        categories = categories.filter(c => c.id !== Number(id));
        saveCategories(categories);
        showToast('Đã xóa danh mục!', 'info');
        renderCategoriesTable();
    }
}

function toggleCatStatus(id) {
    const categories = getCategories();
    const cat = categories.find(c => c.id === Number(id));
    if (cat) {
        cat.isActive = !cat.isActive;
        saveCategories(categories);
        renderCategoriesTable();
    }
}

function deleteBrand(id) {
    if (confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')) {
        let brands = getBrands();
        brands = brands.filter(b => b.id !== Number(id));
        saveBrands(brands);
        showToast('Đã xóa thương hiệu!', 'info');
        renderBrandsTable();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminCategories();
});
