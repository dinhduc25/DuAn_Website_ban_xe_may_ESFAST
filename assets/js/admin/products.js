/**
 * ESFAST ADMIN - PRODUCTS & VARIANTS MANAGEMENT (VANILLA JS)
 * Full CRUD for Electric Motorbikes and Color/Version Variants in LocalStorage
 */

let editingProductId = null;
let currentVariantProductId = null;

function initAdminProducts() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    // Populate category dropdown in modal
    const categories = getCategories();
    const catSelect = document.getElementById('prodCategory');
    const filterCatSelect = document.getElementById('adminProdCategoryFilter');

    if (catSelect) {
        catSelect.innerHTML = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    }
    if (filterCatSelect) {
        filterCatSelect.innerHTML = '<option value="all">Tất cả danh mục</option>' + categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    }

    renderProductsTable();
}

function renderProductsTable() {
    const products = getProducts();
    const categories = getCategories();
    const search = document.getElementById('adminProdSearch')?.value.trim().toLowerCase() || '';
    const catFilter = document.getElementById('adminProdCategoryFilter')?.value || 'all';

    const filtered = products.filter(p => {
        if (catFilter !== 'all' && p.categoryId !== Number(catFilter)) return false;
        if (search) {
            const matchName = p.name.toLowerCase().includes(search);
            const matchSku = p.sku.toLowerCase().includes(search);
            if (!matchName && !matchSku) return false;
        }
        return true;
    });

    const tbody = document.getElementById('adminProductsTableBody');
    if (!tbody) return;

    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">Không tìm thấy mẫu xe nào.</td></tr>';
        return;
    }

    tbody.innerHTML = filtered.map(p => {
        const cat = categories.find(c => c.id === p.categoryId);
        return `
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 16px;">
                    <img src="../${p.image}" alt="${p.name}" style="width: 50px; height: 50px; object-fit: contain; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                </td>
                <td style="padding: 12px 16px;">
                    <div style="font-weight: 700; color: #0f172a;">${p.name}</div>
                    <div style="font-size: 0.8rem; color: #64748b;">SKU: <strong style="color: #0052cc;">${p.sku}</strong></div>
                </td>
                <td style="padding: 12px 16px; font-size: 0.88rem; color: #334155;">
                    ${cat ? cat.name : 'Chưa phân loại'}
                </td>
                <td style="padding: 12px 16px;">
                    <div style="font-weight: 700; color: #0052cc;">${formatMoney(p.salePrice || p.price)}</div>
                    ${p.salePrice && p.price > p.salePrice ? `<div style="font-size: 0.78rem; color: #94a3b8; text-decoration: line-through;">${formatMoney(p.price)}</div>` : ''}
                </td>
                <td style="padding: 12px 16px; font-weight: 700; color: ${p.stockQuantity > 0 ? '#10b981' : '#ef4444'};">
                    ${p.stockQuantity} chiếc
                </td>
                <td style="padding: 12px 16px;">
                    <button type="button" onclick="toggleProductStatus(${p.id})" style="border: none; background: ${p.isActive ? '#ecfdf5' : '#fef2f2'}; color: ${p.isActive ? '#047857' : '#b91c1c'}; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                        ${p.isActive ? 'Đang kinh doanh' : 'Tạm dừng'}
                    </button>
                </td>
                <td style="padding: 12px 16px; text-align: right;">
                    <div style="display: inline-flex; gap: 6px;">
                        <button type="button" onclick="openVariantsModal(${p.id})" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem; border-radius: 6px;" title="Quản lý biến thể màu sắc">
                            <i class="fas fa-palette"></i> Biến thể (${p.variants ? p.variants.length : 0})
                        </button>
                        <button type="button" onclick="openEditProductModal(${p.id})" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #0052cc; padding: 5px 10px; border-radius: 6px; cursor: pointer;" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" onclick="deleteProduct(${p.id})" style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 5px 10px; border-radius: 6px; cursor: pointer;" title="Xóa xe">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function openAddProductModal() {
    editingProductId = null;
    const modal = document.getElementById('productFormModal');
    const form = document.getElementById('productForm');
    document.getElementById('productModalTitle').textContent = 'Thêm Mới Mẫu Xe Máy Điện';
    form.reset();
    document.getElementById('prodImagePreview').src = '../images/bike_theon_s.png';
    modal.style.display = 'flex';
}

function openEditProductModal(id) {
    editingProductId = id;
    const product = getProductById(id);
    if (!product) return;

    const modal = document.getElementById('productFormModal');
    const form = document.getElementById('productForm');
    document.getElementById('productModalTitle').textContent = `Chỉnh Sửa Xe: ${product.name}`;

    form.name.value = product.name;
    form.sku.value = product.sku;
    form.categoryId.value = product.categoryId;
    form.price.value = product.price;
    form.salePrice.value = product.salePrice || '';
    form.stockQuantity.value = product.stockQuantity;
    form.image.value = product.image;
    document.getElementById('prodImagePreview').src = `../${product.image}`;

    // Specs
    form.maxSpeed.value = product.specs.maxSpeed;
    form.maxDistance.value = product.specs.maxDistance;
    form.motorPower.value = product.specs.motorPower;
    form.batteryType.value = product.specs.batteryType;
    form.batteryCapacity.value = product.specs.batteryCapacity;
    form.chargingTime.value = product.specs.chargingTime;
    form.weight.value = product.specs.weight;
    form.description.value = product.description;

    modal.style.display = 'flex';
}

function closeProductModal() {
    document.getElementById('productFormModal').style.display = 'none';
}

function handleSaveProduct(e) {
    e.preventDefault();
    const form = e.target;
    const products = getProducts();

    const productData = {
        name: form.name.value.trim(),
        sku: form.sku.value.trim().toUpperCase(),
        categoryId: Number(form.categoryId.value),
        brandId: 1, // ESFAST
        price: Number(form.price.value),
        salePrice: form.salePrice.value ? Number(form.salePrice.value) : Number(form.price.value),
        stockQuantity: Number(form.stockQuantity.value),
        image: form.image.value.trim(),
        description: form.description.value.trim(),
        specs: {
            maxSpeed: Number(form.maxSpeed.value),
            maxDistance: Number(form.maxDistance.value),
            motorPower: form.motorPower.value.trim(),
            batteryType: form.batteryType.value.trim(),
            batteryCapacity: form.batteryCapacity.value.trim(),
            chargingTime: form.chargingTime.value.trim(),
            weight: form.weight.value.trim(),
            waterproof: 'IP67',
            trunkCapacity: '22 lít'
        }
    };

    if (editingProductId) {
        // Edit existing
        const idx = products.findIndex(p => p.id === editingProductId);
        if (idx > -1) {
            products[idx] = {
                ...products[idx],
                ...productData
            };
            saveProducts(products);
            showToast('Cập nhật thông tin dòng xe thành công!', 'success');
        }
    } else {
        // Add new
        const newId = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
        const newProduct = {
            id: newId,
            slug: productData.name.toLowerCase().replace(/\s+/g, '-'),
            ...productData,
            variants: [
                { id: newId * 100 + 1, sku: `${productData.sku}-STD`, colorName: 'Xanh Công Nghệ', colorCode: '#0052cc', versionName: 'Bản Tiêu Chuản', price: productData.salePrice, stock: productData.stockQuantity }
            ],
            isActive: true,
            isFeatured: true,
            isNew: true,
            isBestseller: false,
            viewCount: 0
        };
        products.unshift(newProduct);
        saveProducts(products);
        showToast('Thêm mới mẫu xe điện thành công!', 'success');
    }

    closeProductModal();
    renderProductsTable();
}

function deleteProduct(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mẫu xe này khỏi hệ thống? Thao tác này không thể hoàn tác.')) {
        let products = getProducts();
        products = products.filter(p => p.id !== Number(id));
        saveProducts(products);
        showToast('Đã xóa sản phẩm thành công!', 'info');
        renderProductsTable();
    }
}

function toggleProductStatus(id) {
    const products = getProducts();
    const prod = products.find(p => p.id === Number(id));
    if (prod) {
        prod.isActive = !prod.isActive;
        saveProducts(products);
        showToast(`Đã ${prod.isActive ? 'kích hoạt' : 'tạm dừng'} kinh doanh xe ${prod.name}!`, 'info');
        renderProductsTable();
    }
}

// ------------------- VARIANTS MANAGEMENT -------------------
function openVariantsModal(productId) {
    currentVariantProductId = productId;
    const product = getProductById(productId);
    if (!product) return;

    document.getElementById('variantModalProductName').textContent = product.name;
    renderVariantsList();
    document.getElementById('variantsModal').style.display = 'flex';
}

function closeVariantsModal() {
    document.getElementById('variantsModal').style.display = 'none';
}

function renderVariantsList() {
    const product = getProductById(currentVariantProductId);
    const container = document.getElementById('variantsListContainer');
    if (!container || !product) return;

    const variants = product.variants || [];
    if (variants.length === 0) {
        container.innerHTML = '<div style="padding: 20px; text-align: center; color: #94a3b8;">Chưa có biến thể màu sắc nào.</div>';
        return;
    }

    container.innerHTML = variants.map((v, idx) => `
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; background: #ffffff;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 22px; height: 22px; border-radius: 50%; background: ${v.colorCode}; border: 1px solid #cbd5e1;"></span>
                <div>
                    <strong style="color: #0f172a; font-size: 0.9rem;">${v.versionName} - ${v.colorName}</strong>
                    <div style="font-size: 0.78rem; color: #64748b;">SKU: ${v.sku} | Kho: <strong>${v.stock}</strong> chiếc</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <strong style="color: #0052cc; font-size: 0.95rem;">${formatMoney(v.price)}</strong>
                <button type="button" onclick="deleteVariant(${idx})" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="Xóa biến thể">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function handleAddVariantSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const product = getProductById(currentVariantProductId);
    if (!product) return;

    if (!product.variants) product.variants = [];

    const newVariant = {
        id: Date.now(),
        sku: form.varSku.value.trim().toUpperCase(),
        versionName: form.varVersion.value.trim(),
        colorName: form.varColorName.value.trim(),
        colorCode: form.varColorCode.value,
        price: Number(form.varPrice.value),
        stock: Number(form.varStock.value)
    };

    product.variants.push(newVariant);

    // Update product total stock
    product.stockQuantity = product.variants.reduce((sum, v) => sum + v.stock, 0);

    const products = getProducts();
    const idx = products.findIndex(p => p.id === product.id);
    if (idx > -1) {
        products[idx] = product;
        saveProducts(products);
    }

    form.reset();
    renderVariantsList();
    renderProductsTable();
    showToast('Đã thêm biến thể mới!', 'success');
}

function deleteVariant(variantIdx) {
    const product = getProductById(currentVariantProductId);
    if (!product || !product.variants) return;

    if (confirm('Xóa biến thể màu sắc này?')) {
        product.variants.splice(variantIdx, 1);
        product.stockQuantity = product.variants.reduce((sum, v) => sum + v.stock, 0);

        const products = getProducts();
        const idx = products.findIndex(p => p.id === product.id);
        if (idx > -1) {
            products[idx] = product;
            saveProducts(products);
        }

        renderVariantsList();
        renderProductsTable();
        showToast('Đã xóa biến thể!', 'info');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminProducts();
});
