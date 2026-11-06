/**
 * ESFAST ECOMMERCE - PRODUCTS CATALOG SCRIPT (VANILLA JS)
 * Handles Filters, Search, Sorting, and Client-side Pagination
 */

let currentPage = 1;
const itemsPerPage = 6;
let filteredProducts = [];

function initProductsCatalog() {
    const products = getProducts().filter(p => p.isActive);
    const categories = getCategories().filter(c => c.isActive);
    const brands = getBrands().filter(b => b.isActive);

    // 1. Populate Category Filter Radio/Checkboxes
    const categoryFilterContainer = document.getElementById('categoryFilterContainer');
    if (categoryFilterContainer) {
        categoryFilterContainer.innerHTML = `
            <label class="filter-radio-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem; color: #334155;">
                <input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()">
                <span>Tất cả danh mục</span>
            </label>
            ${categories.map(c => `
                <label class="filter-radio-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem; color: #334155;">
                    <input type="radio" name="categoryFilter" value="${c.id}" onchange="applyFilters()">
                    <span>${c.name}</span>
                </label>
            `).join('')}
        `;
    }

    // 2. Populate Brand Filter
    const brandFilterContainer = document.getElementById('brandFilterContainer');
    if (brandFilterContainer) {
        brandFilterContainer.innerHTML = `
            <label class="filter-radio-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem; color: #334155;">
                <input type="radio" name="brandFilter" value="all" checked onchange="applyFilters()">
                <span>Tất cả thương hiệu</span>
            </label>
            ${brands.map(b => `
                <label class="filter-radio-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem; color: #334155;">
                    <input type="radio" name="brandFilter" value="${b.id}" onchange="applyFilters()">
                    <span>${b.name}</span>
                </label>
            `).join('')}
        `;
    }

    // Check URL parameters for preset category or search
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    const searchParam = urlParams.get('search');
    const promoParam = urlParams.get('promo');

    if (categoryParam) {
        const catRadio = document.querySelector(`input[name="categoryFilter"][value="${categoryParam}"]`);
        if (catRadio) catRadio.checked = true;
    }

    if (searchParam) {
        const searchInput = document.getElementById('catalogSearchInput');
        if (searchInput) searchInput.value = searchParam;
    }

    if (promoParam) {
        const promoCheck = document.getElementById('filterOnlyPromo');
        if (promoCheck) promoCheck.checked = true;
    }

    applyFilters();
}

function applyFilters() {
    const products = getProducts().filter(p => p.isActive);

    // Get Filter states
    const selectedCat = document.querySelector('input[name="categoryFilter"]:checked')?.value || 'all';
    const selectedBrand = document.querySelector('input[name="brandFilter"]:checked')?.value || 'all';
    const selectedPrice = document.querySelector('input[name="priceFilter"]:checked')?.value || 'all';
    const selectedSpeed = document.querySelector('input[name="speedFilter"]:checked')?.value || 'all';
    const onlyPromo = document.getElementById('filterOnlyPromo')?.checked || false;
    const searchKeyword = document.getElementById('catalogSearchInput')?.value.trim().toLowerCase() || '';
    const sortBy = document.getElementById('catalogSortSelect')?.value || 'featured';

    // Filter Logic
    filteredProducts = products.filter(p => {
        // Category
        if (selectedCat !== 'all' && p.categoryId !== Number(selectedCat)) return false;

        // Brand
        if (selectedBrand !== 'all' && p.brandId !== Number(selectedBrand)) return false;

        // Search
        if (searchKeyword) {
            const matchName = p.name.toLowerCase().includes(searchKeyword);
            const matchSku = p.sku.toLowerCase().includes(searchKeyword);
            const matchDesc = p.description.toLowerCase().includes(searchKeyword);
            if (!matchName && !matchSku && !matchDesc) return false;
        }

        // Promo
        if (onlyPromo) {
            if (!p.salePrice || p.salePrice >= p.price) return false;
        }

        // Price range
        const finalPrice = p.salePrice || p.price;
        if (selectedPrice === 'under20' && finalPrice >= 20000000) return false;
        if (selectedPrice === '20to35' && (finalPrice < 20000000 || finalPrice > 35000000)) return false;
        if (selectedPrice === '35to50' && (finalPrice < 35000000 || finalPrice > 50000000)) return false;
        if (selectedPrice === 'over50' && finalPrice <= 50000000) return false;

        // Speed filter
        if (selectedSpeed === 'under50' && p.specs.maxSpeed > 50) return false;
        if (selectedSpeed === '50to80' && (p.specs.maxSpeed <= 50 || p.specs.maxSpeed > 80)) return false;
        if (selectedSpeed === 'over80' && p.specs.maxSpeed <= 80) return false;

        return true;
    });

    // Sorting Logic
    if (sortBy === 'price-asc') {
        filteredProducts.sort((a, b) => (a.salePrice || a.price) - (b.salePrice || b.price));
    } else if (sortBy === 'price-desc') {
        filteredProducts.sort((a, b) => (b.salePrice || b.price) - (a.salePrice || a.price));
    } else if (sortBy === 'name-asc') {
        filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'speed-desc') {
        filteredProducts.sort((a, b) => b.specs.maxSpeed - a.specs.maxSpeed);
    } else if (sortBy === 'distance-desc') {
        filteredProducts.sort((a, b) => b.specs.maxDistance - a.specs.maxDistance);
    } else {
        // default featured
        filteredProducts.sort((a, b) => (b.isFeatured ? 1 : 0) - (a.isFeatured ? 1 : 0));
    }

    currentPage = 1;
    renderCatalog();
}

function renderCatalog() {
    const container = document.getElementById('catalogProductsGrid');
    const countLabel = document.getElementById('catalogResultCount');
    const paginationContainer = document.getElementById('catalogPagination');

    if (!container) return;

    if (countLabel) {
        countLabel.textContent = `Tìm thấy ${filteredProducts.length} mẫu xe phù hợp`;
    }

    if (filteredProducts.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <div style="font-size: 3rem; color: #94a3b8; margin-bottom: 16px;"><i class="fas fa-search"></i></div>
                <h3 style="font-size: 1.3rem; color: #0f172a; margin-bottom: 8px;">Không tìm thấy mẫu xe nào</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 20px;">Vui lòng thử điều chỉnh lại bộ lọc giá hoặc từ khóa tìm kiếm.</p>
                <button onclick="resetFilters()" class="btn btn-primary" style="padding: 10px 24px; border-radius: 999px;">
                    <i class="fas fa-sync-alt"></i> Đặt Lại Bộ Lọc
                </button>
            </div>
        `;
        if (paginationContainer) paginationContainer.innerHTML = '';
        return;
    }

    // Pagination slice
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const pageItems = filteredProducts.slice(startIndex, startIndex + itemsPerPage);

    container.innerHTML = pageItems.map(p => {
        const discountPercent = p.salePrice && p.price > p.salePrice ? Math.round(((p.price - p.salePrice) / p.price) * 100) : 0;
        return `
            <div class="product-card" style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column; transition: var(--transition); position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.03);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 14px 28px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.03)';">
                ${discountPercent > 0 ? `
                    <span style="position: absolute; top: 12px; left: 12px; background: #ef4444; color: #ffffff; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; z-index: 2;">
                        -${discountPercent}%
                    </span>
                ` : ''}
                ${p.stockQuantity <= 0 ? `
                    <span style="position: absolute; top: 12px; right: 12px; background: #64748b; color: #ffffff; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; z-index: 2;">
                        Tạm hết hàng
                    </span>
                ` : ''}

                <a href="product-detail.html?id=${p.id}" style="padding: 24px 20px 10px; display: block; text-align: center; background: #f8fafc;">
                    <img src="${p.image}" alt="${p.name}" style="height: 180px; width: 100%; object-fit: contain; margin: 0 auto; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </a>

                <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                    <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">SKU: ${p.sku}</div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 10px;">
                        <a href="product-detail.html?id=${p.id}" style="color: #0f172a; text-decoration: none;">${p.name}</a>
                    </h3>

                    <!-- Specs Preview -->
                    <div style="display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
                        <span style="font-size: 0.78rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: #475569;">
                            <i class="fas fa-tachometer-alt text-primary"></i> ${p.specs.maxSpeed} km/h
                        </span>
                        <span style="font-size: 0.78rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: #475569;">
                            <i class="fas fa-route text-primary"></i> ${p.specs.maxDistance} km/sạc
                        </span>
                        <span style="font-size: 0.78rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: #475569;">
                            <i class="fas fa-bolt text-primary"></i> ${p.specs.motorPower}
                        </span>
                    </div>

                    <!-- Price -->
                    <div style="margin-top: auto; margin-bottom: 16px;">
                        <div style="font-size: 1.25rem; font-weight: 800; color: #0052cc;">
                            ${formatMoney(p.salePrice || p.price)}
                        </div>
                        ${p.salePrice && p.price > p.salePrice ? `
                            <div style="font-size: 0.85rem; color: #94a3b8; text-decoration: line-through;">
                                ${formatMoney(p.price)}
                            </div>
                        ` : ''}
                    </div>

                    <!-- Actions -->
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 8px;">
                        <a href="product-detail.html?id=${p.id}" class="btn btn-outline" style="text-align: center; font-size: 0.88rem; padding: 8px 14px; border-radius: 8px; font-weight: 600; text-decoration: none;">
                            Xem Chi Tiết
                        </a>
                        <button onclick="quickAddToCartCatalog(${p.id})" style="background: rgba(0,82,204,0.1); border: 1px solid rgba(0,82,204,0.2); color: #0052cc; border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" title="Thêm vào giỏ">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    // Render Pagination
    if (paginationContainer) {
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
        } else {
            let pagHtml = `
                <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled style="opacity: 0.4; cursor: not-allowed;"' : ''} style="padding: 8px 14px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer;">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `
                    <button onclick="changePage(${i})" style="padding: 8px 16px; border-radius: 8px; border: 1px solid ${i === currentPage ? '#0052cc' : '#cbd5e1'}; background: ${i === currentPage ? '#0052cc' : '#ffffff'}; color: ${i === currentPage ? '#ffffff' : '#334155'}; font-weight: 700; cursor: pointer;">
                        ${i}
                    </button>
                `;
            }
            pagHtml += `
                <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled style="opacity: 0.4; cursor: not-allowed;"' : ''} style="padding: 8px 14px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
            paginationContainer.innerHTML = pagHtml;
        }
    }
}

function changePage(page) {
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderCatalog();
    window.scrollTo({ top: 150, behavior: 'smooth' });
}

function resetFilters() {
    const defaultCat = document.querySelector('input[name="categoryFilter"][value="all"]');
    if (defaultCat) defaultCat.checked = true;

    const defaultBrand = document.querySelector('input[name="brandFilter"][value="all"]');
    if (defaultBrand) defaultBrand.checked = true;

    const defaultPrice = document.querySelector('input[name="priceFilter"][value="all"]');
    if (defaultPrice) defaultPrice.checked = true;

    const defaultSpeed = document.querySelector('input[name="speedFilter"][value="all"]');
    if (defaultSpeed) defaultSpeed.checked = true;

    const promoCheck = document.getElementById('filterOnlyPromo');
    if (promoCheck) promoCheck.checked = false;

    const searchInput = document.getElementById('catalogSearchInput');
    if (searchInput) searchInput.value = '';

    const sortSelect = document.getElementById('catalogSortSelect');
    if (sortSelect) sortSelect.value = 'featured';

    applyFilters();
}

function quickAddToCartCatalog(id) {
    const res = addToCart(id, null, 1);
    if (res.success) {
        showToast(res.message, 'success');
    } else {
        showToast(res.message, 'danger');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initProductsCatalog();
});
