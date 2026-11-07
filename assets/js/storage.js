/**
 * ESFAST ECOMMERCE - CENTRAL STORAGE ENGINE (VANILLA JAVASCRIPT)
 * Manages all client-side data via LocalStorage & SessionStorage
 * 100% Offline / Client-side simulation, No PHP, No SQL
 */

const STORAGE_KEYS = {
    USERS: 'esfast_users',
    PRODUCTS: 'esfast_products',
    CATEGORIES: 'esfast_categories',
    BRANDS: 'esfast_brands',
    ORDERS: 'esfast_orders',
    VOUCHERS: 'esfast_vouchers',
    BANNERS: 'esfast_banners',
    CART: 'esfast_cart',
    CURRENT_USER: 'esfast_current_user',
    INITIALIZED: 'esfast_initialized_v2'
};

// Initial Seed Data
const DEFAULT_USERS = [
    {
        id: 1,
        username: 'admin',
        email: 'admin@esfast.vn',
        password: 'admin123',
        fullName: 'Quản Trị Viên ESFAST',
        phone: '0901234567',
        address: 'Tòa nhà ESFAST Tower, Quận Cầu Giấy, Hà Nội',
        role: 'admin',
        status: 'active',
        createdAt: '2026-01-01'
    },
    {
        id: 2,
        username: 'customer',
        email: 'customer@esfast.vn',
        password: '123456',
        fullName: 'Nguyễn Văn Khách Hàng',
        phone: '0987654321',
        address: 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội',
        role: 'customer',
        status: 'active',
        createdAt: '2026-02-15'
    },
    {
        id: 3,
        username: 'tranminh',
        email: 'minh.tran@gmail.com',
        password: '123456',
        fullName: 'Trần Quang Minh',
        phone: '0912888999',
        address: 'Số 45 Lê Lợi, Phường Bến Nghé, Quận 1, TP. HCM',
        role: 'customer',
        status: 'active',
        createdAt: '2026-03-10'
    },
    {
        id: 4,
        username: 'lehoang',
        email: 'hoang.le@gmail.com',
        password: '123456',
        fullName: 'Lê Huy Hoàng',
        phone: '0933444555',
        address: 'Số 88 Cầu Giấy, Hà Nội',
        role: 'customer',
        status: 'locked',
        createdAt: '2026-04-05'
    },
    {
        id: 5,
        username: 'shopmanager',
        email: 'shop@esfast.vn',
        password: 'shop123',
        fullName: 'Nguyễn Thị Quản Lý',
        phone: '0909888777',
        address: 'Showroom ESFAST, Hà Nội',
        role: 'shop_manager',
        status: 'active',
        createdAt: '2026-02-01'
    }
];

const DEFAULT_CATEGORIES = [
    { id: 1, name: 'Xe Máy Điện Cao Cấp', slug: 'xe-may-dien-cao-cap', description: 'Dòng xe sang trọng, động cơ mạnh mẽ vượt trội và công nghệ kết nối thông minh cao cấp.', icon: 'fa-bolt', isActive: true },
    { id: 2, name: 'Xe Máy Điện Đô Thị', slug: 'xe-may-dien-do-thi', description: 'Thiết kế thời thượng, nhỏ gọn linh hoạt, tối ưu cho nhu cầu di chuyển hàng ngày.', icon: 'fa-city', isActive: true },
    { id: 3, name: 'Xe Học Sinh - Sinh Viên', slug: 'xe-hoc-sinh-sinh-vien', description: 'Trọng lượng nhẹ, an toàn tuyệt đối, giới hạn tốc độ 49km/h, không cần bằng lái.', icon: 'fa-graduation-cap', isActive: true },
    { id: 4, name: 'Xe Máy Điện Thể Thao', slug: 'xe-may-dien-the-thao', description: 'Kiểu dáng khí động học mạnh mẽ, khả năng tăng tốc bứt phá và công suất động cơ khủng.', icon: 'fa-tachometer-alt', isActive: true }
];

const DEFAULT_BRANDS = [
    { id: 1, name: 'ESFAST Official', slug: 'esfast-official', description: 'Thương hiệu xe máy điện tiên phong công nghệ pin LFP', isActive: true },
    { id: 2, name: 'VinFast E-Scooter', slug: 'vinfast-escooter', description: 'Dòng xe máy điện quốc dân Việt Nam', isActive: true },
    { id: 3, name: 'Dat Bike', slug: 'dat-bike', description: 'Xe điện phong cách cổ điển và thể thao mạnh mẽ', isActive: true },
    { id: 4, name: 'Yadea Tech', slug: 'yadea-tech', description: 'Thương hiệu xe điện toàn cầu ắc quy Graphene', isActive: true }
];

const DEFAULT_PRODUCTS = [
    {
        id: 1,
        sku: 'ESF-THEONS',
        name: 'ESFAST Theon S',
        slug: 'esfast-theon-s',
        categoryId: 1,
        brandId: 1,
        price: 63000000,
        salePrice: 56900000,
        image: 'images/bike_theon_s.png',
        images: ['images/bike_theon_s.png', 'images/bike_fallback.png'],
        description: 'Siêu xe máy điện cao cấp dẫn đầu phân khúc, trang bị động cơ điện trung tâm 7100W mạnh mẽ cùng hệ thống phanh ABS 2 kênh an toàn đỉnh cao.',
        content: 'ESFAST Theon S là biểu tượng công nghệ và sức mạnh của thương hiệu xe điện ESFAST. Xe sở hữu động cơ đặt giữa truyền động bằng dây curoa Continental danh tiếng thế giới, cho công suất tối đa lên đến 7100W và mô-men xoắn tức thời ấn tượng.',
        specs: {
            maxSpeed: 99,
            maxDistance: 150,
            motorPower: '7100W',
            batteryType: 'Pin LFP công nghệ cao',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '145 kg',
            waterproof: 'IP67 (Lội nước 0.5m trong 30 phút)',
            trunkCapacity: '24 lít'
        },
        variants: [
            { id: 101, sku: 'ESF-THEONS-BLU', colorName: 'Xanh Công Nghệ Tech Blue', colorCode: '#0052cc', versionName: 'Bản Cao Cấp (Kèm Pin LFP)', price: 56900000, stock: 12 },
            { id: 102, sku: 'ESF-THEONS-BLK', colorName: 'Đen Nhám Carbon', colorCode: '#1e232a', versionName: 'Bản Cao Cấp (Kèm Pin LFP)', price: 56900000, stock: 8 },
            { id: 103, sku: 'ESF-THEONS-RED', colorName: 'Đỏ Đô Sang Trọng', colorCode: '#8b0000', versionName: 'Bản Cao Cấp (Kèm Pin LFP)', price: 56900000, stock: 5 }
        ],
        stockQuantity: 25,
        isActive: true,
        isFeatured: true,
        isNew: true,
        isBestseller: false,
        viewCount: 1540
    },
    {
        id: 2,
        sku: 'ESF-VENTOS',
        name: 'ESFAST Vento S',
        slug: 'esfast-vento-s',
        categoryId: 4,
        brandId: 1,
        price: 50000000,
        salePrice: 46000000,
        image: 'images/bike_vento_s.png',
        images: ['images/bike_vento_s.png', 'images/bike_fallback.png'],
        description: 'Mẫu xe tay ga điện thể thao sở hữu thiết kế năng động, công nghệ mở khóa thông minh Phone As A Key (PAAK) và khả năng vận hành êm ái vượt trội.',
        content: 'ESFAST Vento S chinh phục người dùng trẻ đam mê tốc độ và phong cách thể thao cá tính. Xe trang bị động cơ bên (Side Motor) thế hệ mới công suất 5200W, mang lại khả năng tăng tốc mượt mà.',
        specs: {
            maxSpeed: 89,
            maxDistance: 160,
            motorPower: '5200W',
            batteryType: 'Pin LFP thế hệ mới',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '122 kg',
            waterproof: 'IP67',
            trunkCapacity: '17 lít'
        },
        variants: [
            { id: 201, sku: 'ESF-VENTOS-WHT', colorName: 'Trắng Ngọc Trai', colorCode: '#f4f6f8', versionName: 'Bản Thể Thao', price: 46000000, stock: 15 },
            { id: 202, sku: 'ESF-VENTOS-BLU', colorName: 'Xanh Cyan Neon', colorCode: '#00d2ff', versionName: 'Bản Thể Thao', price: 46000000, stock: 12 },
            { id: 203, sku: 'ESF-VENTOS-YEL', colorName: 'Vàng Nắng Nổi Bật', colorCode: '#f59e0b', versionName: 'Bản Thể Thao', price: 46000000, stock: 8 }
        ],
        stockQuantity: 35,
        isActive: true,
        isFeatured: true,
        isNew: true,
        isBestseller: true,
        viewCount: 1280
    },
    {
        id: 3,
        sku: 'ESF-KLARAS2',
        name: 'ESFAST Klara S2',
        slug: 'esfast-klara-s2',
        categoryId: 1,
        brandId: 1,
        price: 35000000,
        salePrice: 32500000,
        image: 'images/bike_klara_s2.png',
        images: ['images/bike_klara_s2.png', 'images/bike_fallback.png'],
        description: 'Thiết kế phong cách châu Âu thanh lịch và quyến rũ, phù hợp hoàn hảo với nhân viên công sở và phái đẹp hiện đại.',
        content: 'Lấy cảm hứng từ nét đẹp tinh tế của nước Ý, ESFAST Klara S2 tôn vinh vẻ đẹp trang nhã của người lái với đường cong vuốt mềm mại và hệ thống đèn full-LED hình cánh chim đặc trưng.',
        specs: {
            maxSpeed: 78,
            maxDistance: 194,
            motorPower: '3000W',
            batteryType: 'Pin LFP cao cấp',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '112 kg',
            waterproof: 'IP67',
            trunkCapacity: '23 lít'
        },
        variants: [
            { id: 301, sku: 'ESF-KLARA-WHT', colorName: 'Trắng Tinh Khôi', colorCode: '#ffffff', versionName: 'Bản Phong Cách Ý', price: 32500000, stock: 18 },
            { id: 302, sku: 'ESF-KLARA-GRN', colorName: 'Xanh Rêu Sage', colorCode: '#405d4b', versionName: 'Bản Phong Cách Ý', price: 32500000, stock: 12 },
            { id: 303, sku: 'ESF-KLARA-BLK', colorName: 'Đen Bóng Huyền Bí', colorCode: '#111827', versionName: 'Bản Phong Cách Ý', price: 32500000, stock: 10 }
        ],
        stockQuantity: 40,
        isActive: true,
        isFeatured: true,
        isNew: false,
        isBestseller: true,
        viewCount: 2100
    },
    {
        id: 4,
        sku: 'ESF-FELIZS',
        name: 'ESFAST Feliz S',
        slug: 'esfast-feliz-s',
        categoryId: 2,
        brandId: 1,
        price: 27000000,
        salePrice: 24900000,
        image: 'images/bike_feliz_s.png',
        images: ['images/bike_feliz_s.png', 'images/bike_fallback.png'],
        description: 'Mẫu xe điện đô thị thời thượng, cân bằng hoàn hảo giữa tính thực dụng, cốp xe cực đại 25 lít và chi phí vận hành siêu rẻ.',
        content: 'ESFAST Feliz S đem đến giải pháp chuyển đổi xanh dễ tiếp cận nhất cho mọi gia đình. Sở hữu chiều cao yên 770mm lý tưởng cho vóc dáng người Việt, xe vận hành êm ái.',
        specs: {
            maxSpeed: 78,
            maxDistance: 198,
            motorPower: '3000W',
            batteryType: 'Pin LFP công nghệ mới',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '110 kg',
            waterproof: 'IP67',
            trunkCapacity: '25 lít'
        },
        variants: [
            { id: 401, sku: 'ESF-FELIZ-SLV', colorName: 'Bạc Ánh Kim', colorCode: '#9ca3af', versionName: 'Bản Tiêu Chuẩn', price: 24900000, stock: 25 },
            { id: 402, sku: 'ESF-FELIZ-RED', colorName: 'Đỏ Lửa Đô Thị', colorCode: '#dc2626', versionName: 'Bản Tiêu Chuẩn', price: 24900000, stock: 18 },
            { id: 403, sku: 'ESF-FELIZ-BLU', colorName: 'Xanh Biển Sâu', colorCode: '#1d4ed8', versionName: 'Bản Tiêu Chuẩn', price: 24900000, stock: 12 }
        ],
        stockQuantity: 55,
        isActive: true,
        isFeatured: true,
        isNew: true,
        isBestseller: false,
        viewCount: 1890
    },
    {
        id: 5,
        sku: 'ESF-EVO200',
        name: 'ESFAST Evo 200',
        slug: 'esfast-evo-200',
        categoryId: 2,
        brandId: 1,
        price: 22000000,
        salePrice: 19900000,
        image: 'images/bike_evo_200.png',
        images: ['images/bike_evo_200.png', 'images/bike_fallback.png'],
        description: 'Xe máy điện quốc dân bán chạy số 1 thị trường với kỷ lục di chuyển 203 km chỉ sau một lần sạc đầy.',
        content: 'ESFAST Evo 200 đã tạo nên cơn sốt trên thị trường xe máy điện với mức giá hợp lý và quãng đường kỷ lục 203km/sạc. Trọng lượng nhẹ chỉ 97kg cùng bán kính quay vòng nhỏ.',
        specs: {
            maxSpeed: 70,
            maxDistance: 203,
            motorPower: '2500W',
            batteryType: 'Pin LFP tuổi thọ cao',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '97 kg',
            waterproof: 'IP67',
            trunkCapacity: '22 lít'
        },
        variants: [
            { id: 501, sku: 'ESF-EVO200-WHT', colorName: 'Trắng Ngọc Trai', colorCode: '#f8fafc', versionName: 'Bản Quốc Dân', price: 19900000, stock: 30 },
            { id: 502, sku: 'ESF-EVO200-BLU', colorName: 'Xanh Công Nghệ', colorCode: '#0284c7', versionName: 'Bản Quốc Dân', price: 19900000, stock: 28 },
            { id: 503, sku: 'ESF-EVO200-BLK', colorName: 'Đen Nhám', colorCode: '#18181b', versionName: 'Bản Quốc Dân', price: 19900000, stock: 22 }
        ],
        stockQuantity: 80,
        isActive: true,
        isFeatured: true,
        isNew: false,
        isBestseller: true,
        viewCount: 4320
    },
    {
        id: 6,
        sku: 'ESF-EVO200LITE',
        name: 'ESFAST Evo 200 Lite',
        slug: 'esfast-evo-200-lite',
        categoryId: 3,
        brandId: 1,
        price: 19400000,
        salePrice: 17900000,
        image: 'images/bike_evo_lite.png',
        images: ['images/bike_evo_lite.png', 'images/bike_fallback.png'],
        description: 'Lựa chọn số 1 cho học sinh THPT và sinh viên, giới hạn tốc độ 49 km/h an toàn, không cần bằng lái A1.',
        content: 'Được thiết kế riêng theo quy chuẩn an toàn dành cho lứa tuổi học sinh, ESFAST Evo 200 Lite sở hữu đầy đủ tiện nghi của dòng Evo danh tiếng.',
        specs: {
            maxSpeed: 49,
            maxDistance: 205,
            motorPower: '1500W',
            batteryType: 'Pin LFP tiêu chuẩn',
            batteryCapacity: '3.5 kWh',
            chargingTime: '6 giờ',
            weight: '95 kg',
            waterproof: 'IP67',
            trunkCapacity: '22 lít'
        },
        variants: [
            { id: 601, sku: 'ESF-EVOLITE-YEL', colorName: 'Vàng Năng Động', colorCode: '#fbbf24', versionName: 'Bản Học Sinh', price: 17900000, stock: 25 },
            { id: 602, sku: 'ESF-EVOLITE-WHT', colorName: 'Trắng Trẻ Trung', colorCode: '#ffffff', versionName: 'Bản Học Sinh', price: 17900000, stock: 25 },
            { id: 603, sku: 'ESF-EVOLITE-RED', colorName: 'Đỏ Thể Thao', colorCode: '#ef4444', versionName: 'Bản Học Sinh', price: 17900000, stock: 10 }
        ],
        stockQuantity: 60,
        isActive: true,
        isFeatured: true,
        isNew: true,
        isBestseller: false,
        viewCount: 3100
    },
    {
        id: 7,
        sku: 'ESF-IMPES',
        name: 'ESFAST Impes Sport',
        slug: 'esfast-impes-sport',
        categoryId: 3,
        brandId: 1,
        price: 16500000,
        salePrice: 14900000,
        image: 'images/bike_impes.png',
        images: ['images/bike_impes.png', 'images/bike_fallback.png'],
        description: 'Xe máy điện cá tính phong cách đường phố, khung thép chịu lực cao và giảm xóc đơn thể thao Mono Shock.',
        content: 'ESFAST Impes Sport sở hữu phong cách góc cạnh bụi bặm được giới trẻ yêu thích. Khung gầm gia cường tiêu chuẩn quốc tế giúp xe chịu tải trọng lớn.',
        specs: {
            maxSpeed: 50,
            maxDistance: 70,
            motorPower: '1200W',
            batteryType: 'Pin Lithium-ion tháo rời',
            batteryCapacity: '22 Ah',
            chargingTime: '4 giờ',
            weight: '75 kg',
            waterproof: 'IP67',
            trunkCapacity: '10 lít'
        },
        variants: [
            { id: 701, sku: 'ESF-IMPES-BLU', colorName: 'Xanh Lá Neon', colorCode: '#10b981', versionName: 'Bản Thể Thao', price: 14900000, stock: 15 },
            { id: 702, sku: 'ESF-IMPES-BLK', colorName: 'Đen Thể Thao', colorCode: '#1f2937', versionName: 'Bản Thể Thao', price: 14900000, stock: 15 }
        ],
        stockQuantity: 30,
        isActive: true,
        isFeatured: false,
        isNew: false,
        isBestseller: false,
        viewCount: 950
    },
    {
        id: 8,
        sku: 'ESF-APEXX',
        name: 'ESFAST Apex X Hyper-E',
        slug: 'esfast-apex-x',
        categoryId: 4,
        brandId: 1,
        price: 75000000,
        salePrice: 69900000,
        image: 'images/bike_apex_x.png',
        images: ['images/bike_apex_x.png', 'images/bike_fallback.png'],
        description: 'Chiến mã đường phố phong cách mô tô phân khối lớn chạy điện hoàn toàn, tăng tốc 0-60 km/h chỉ 4.2 giây.',
        content: 'ESFAST Apex X là đỉnh cao công nghệ chế tác xe máy điện hai bánh. Khung sườn hợp kim nhôm đúc nguyên khối siêu nhẹ kết hợp cùng bộ điều khiển FOC kép thông minh.',
        specs: {
            maxSpeed: 110,
            maxDistance: 180,
            motorPower: '8000W',
            batteryType: 'Pin LFP Dual Pack kép',
            batteryCapacity: '4.2 kWh',
            chargingTime: '5 giờ',
            weight: '138 kg',
            waterproof: 'IP67',
            trunkCapacity: '15 lít'
        },
        variants: [
            { id: 801, sku: 'ESF-APEX-PUR', colorName: 'Tím Cyberpunk', colorCode: '#6366f1', versionName: 'Bản Giới Hạn', price: 69900000, stock: 8 },
            { id: 802, sku: 'ESF-APEX-BLK', colorName: 'Đen Titan', colorCode: '#0f172a', versionName: 'Bản Giới Hạn', price: 69900000, stock: 7 }
        ],
        stockQuantity: 15,
        isActive: true,
        isFeatured: true,
        isNew: true,
        isBestseller: true,
        viewCount: 1650
    }
];

const DEFAULT_VOUCHERS = [
    {
        id: 1,
        code: 'ESFAST2026',
        name: 'Mừng Ra Mắt Thương Hiệu ESFAST',
        type: 'fixed',
        value: 1000000,
        maxDiscount: 1000000,
        minOrder: 15000000,
        usageLimit: 500,
        usedCount: 28,
        startDate: '2026-01-01',
        endDate: '2026-12-31',
        isActive: true
    },
    {
        id: 2,
        code: 'VIPXANH',
        name: 'Ưu Đãi Đặc Quyền Khách Hàng VIP',
        type: 'percentage',
        value: 5,
        maxDiscount: 2000000,
        minOrder: 25000000,
        usageLimit: 100,
        usedCount: 14,
        startDate: '2026-01-01',
        endDate: '2026-12-31',
        isActive: true
    },
    {
        id: 3,
        code: 'CHAOHEXANH',
        name: 'Khuyến Mãi Chào Mùa Hè Xanh',
        type: 'fixed',
        value: 500000,
        maxDiscount: 500000,
        minOrder: 10000000,
        usageLimit: 300,
        usedCount: 42,
        startDate: '2026-01-01',
        endDate: '2026-12-31',
        isActive: true
    },
    {
        id: 4,
        code: 'FREESHIP',
        name: 'Miễn Phí Vận Chuyển Toàn Quốc',
        type: 'fixed',
        value: 300000,
        maxDiscount: 300000,
        minOrder: 5000000,
        usageLimit: 1000,
        usedCount: 85,
        startDate: '2026-01-01',
        endDate: '2026-12-31',
        isActive: true
    }
];

const DEFAULT_BANNERS = [
    {
        id: 1,
        title: 'ESFAST THEON S – ĐỈNH CAO XE MÁY ĐIỆN VIỆT NAM',
        subtitle: 'Tốc độ 99 km/h • Động cơ 7100W • Phanh ABS Continental 2 Kênh An Toàn Tuyệt Đối',
        image: 'images/banner_theon_s.jpg',
        link: 'product-detail.html?id=1',
        badge: 'SIÊU PHẨM CÔNG NGHỆ',
        isActive: true,
        sortOrder: 1
    },
    {
        id: 2,
        title: 'ESFAST EVO 200 – DI CHUYỂN 203KM CHỈ VỚI 1 LẦN SẠC',
        subtitle: 'Mẫu xe điện bán chạy nhất • Tiết kiệm 80% chi phí vận hành so với xe xăng',
        image: 'images/banner_evo200.jpg',
        link: 'product-detail.html?id=5',
        badge: 'XE ĐIỆN QUỐC DÂN',
        isActive: true,
        sortOrder: 2
    },
    {
        id: 3,
        title: 'CHƯƠNG TRÌNH ĐỔI XĂNG LẤY ĐIỆN – TRỢ GIÁ ĐẾN 3.000.000 Đ',
        subtitle: 'Đón đầu kỷ nguyên giao thông xanh, nhận ngay gói sạc miễn phí 1 năm tại trạm ESFAST',
        image: 'images/banner_green_summer.jpg',
        link: 'products.html',
        badge: 'ƯU ĐÃI ĐẶC BIỆT',
        isActive: true,
        sortOrder: 3
    }
];

const DEFAULT_ORDERS = [
    {
        id: 1,
        orderCode: 'ESF-20261101-001',
        userId: 2,
        customerName: 'Nguyễn Văn Khách Hàng',
        customerEmail: 'customer@esfast.vn',
        customerPhone: '0987654321',
        shippingAddress: 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội',
        notes: 'Giao xe trong giờ hành chính',
        items: [
            {
                productId: 5,
                productName: 'ESFAST Evo 200',
                variantId: 502,
                variantName: 'Bản Quốc Dân - Xanh Công Nghệ',
                price: 19900000,
                quantity: 1,
                image: 'images/bike_evo_200.png'
            }
        ],
        subtotal: 19900000,
        discountAmount: 1000000,
        voucherCode: 'ESFAST2026',
        totalAmount: 18900000,
        paymentMethod: 'cod',
        paymentStatus: 'unpaid',
        orderStatus: 'processing',
        createdAt: '2026-11-01 10:30:00'
    },
    {
        id: 2,
        orderCode: 'ESF-20261102-002',
        userId: 3,
        customerName: 'Trần Quang Minh',
        customerEmail: 'minh.tran@gmail.com',
        customerPhone: '0912888999',
        shippingAddress: 'Số 45 Lê Lợi, Phường Bến Nghé, Quận 1, TP. HCM',
        notes: 'Liên hệ trước khi giao 30 phút',
        items: [
            {
                productId: 1,
                productName: 'ESFAST Theon S',
                variantId: 101,
                variantName: 'Bản Cao Cấp - Xanh Công Nghệ Tech Blue',
                price: 56900000,
                quantity: 1,
                image: 'images/bike_theon_s.png'
            }
        ],
        subtotal: 56900000,
        discountAmount: 2000000,
        voucherCode: 'VIPXANH',
        totalAmount: 54900000,
        paymentMethod: 'online_gateway',
        paymentStatus: 'paid',
        orderStatus: 'shipping',
        createdAt: '2026-11-02 14:15:00'
    },
    {
        id: 3,
        orderCode: 'ESF-20261103-003',
        userId: 2,
        customerName: 'Nguyễn Văn Khách Hàng',
        customerEmail: 'customer@esfast.vn',
        customerPhone: '0987654321',
        shippingAddress: 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội',
        notes: '',
        items: [
            {
                productId: 3,
                productName: 'ESFAST Klara S2',
                variantId: 301,
                variantName: 'Bản Phong Cách Ý - Trắng Tinh Khôi',
                price: 32500000,
                quantity: 1,
                image: 'images/bike_klara_s2.png'
            }
        ],
        subtotal: 32500000,
        discountAmount: 500000,
        voucherCode: 'CHAOHEXANH',
        totalAmount: 32000000,
        paymentMethod: 'bank_transfer',
        paymentStatus: 'paid',
        orderStatus: 'completed',
        createdAt: '2026-11-03 09:00:00'
    }
];

// INITIALIZATION
function initStorage() {
    if (!localStorage.getItem(STORAGE_KEYS.INITIALIZED)) {
        localStorage.setItem(STORAGE_KEYS.USERS, JSON.stringify(DEFAULT_USERS));
        localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(DEFAULT_CATEGORIES));
        localStorage.setItem(STORAGE_KEYS.BRANDS, JSON.stringify(DEFAULT_BRANDS));
        localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(DEFAULT_PRODUCTS));
        localStorage.setItem(STORAGE_KEYS.VOUCHERS, JSON.stringify(DEFAULT_VOUCHERS));
        localStorage.setItem(STORAGE_KEYS.BANNERS, JSON.stringify(DEFAULT_BANNERS));
        localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(DEFAULT_ORDERS));
        if (!localStorage.getItem(STORAGE_KEYS.CART)) {
            localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify([]));
        }
        localStorage.setItem(STORAGE_KEYS.INITIALIZED, 'true');
    }
}

// Auto-run init on load
initStorage();

// UTILITY HELPERS
function formatMoney(amount) {
    if (isNaN(amount) || amount === null) return '0 ₫';
    return Number(amount).toLocaleString('vi-VN') + ' ₫';
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('vi-VN', { year: 'numeric', month: '2-digit', day: '2-digit' }) + ' ' +
           d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
}

function getStorage(key, defaultValue = []) {
    try {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : defaultValue;
    } catch (e) {
        console.error('Error reading localStorage for key: ' + key, e);
        return defaultValue;
    }
}

function setStorage(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.error('Error writing localStorage for key: ' + key, e);
    }
}

// USERS & AUTH API
function getUsers() {
    return getStorage(STORAGE_KEYS.USERS, DEFAULT_USERS);
}

function saveUsers(users) {
    setStorage(STORAGE_KEYS.USERS, users);
}

function getCurrentUser() {
    return getStorage(STORAGE_KEYS.CURRENT_USER, null);
}

function setCurrentUser(user) {
    setStorage(STORAGE_KEYS.CURRENT_USER, user);
}

function logoutUser() {
    localStorage.removeItem(STORAGE_KEYS.CURRENT_USER);
}

// PRODUCTS API
function getProducts() {
    return getStorage(STORAGE_KEYS.PRODUCTS, DEFAULT_PRODUCTS);
}

function getProductById(id) {
    const products = getProducts();
    return products.find(p => p.id === Number(id));
}

function saveProducts(products) {
    setStorage(STORAGE_KEYS.PRODUCTS, products);
}

// CATEGORIES & BRANDS API
function getCategories() {
    return getStorage(STORAGE_KEYS.CATEGORIES, DEFAULT_CATEGORIES);
}

function saveCategories(categories) {
    setStorage(STORAGE_KEYS.CATEGORIES, categories);
}

function getBrands() {
    return getStorage(STORAGE_KEYS.BRANDS, DEFAULT_BRANDS);
}

function saveBrands(brands) {
    setStorage(STORAGE_KEYS.BRANDS, brands);
}

// VOUCHERS API
function getVouchers() {
    return getStorage(STORAGE_KEYS.VOUCHERS, DEFAULT_VOUCHERS);
}

function saveVouchers(vouchers) {
    setStorage(STORAGE_KEYS.VOUCHERS, vouchers);
}

// BANNERS API
function getBanners() {
    return getStorage(STORAGE_KEYS.BANNERS, DEFAULT_BANNERS);
}

function saveBanners(banners) {
    setStorage(STORAGE_KEYS.BANNERS, banners);
}

// ORDERS API
function getOrders() {
    return getStorage(STORAGE_KEYS.ORDERS, DEFAULT_ORDERS);
}

function saveOrders(orders) {
    setStorage(STORAGE_KEYS.ORDERS, orders);
}

function addOrder(orderData) {
    const orders = getOrders();
    const newId = orders.length > 0 ? Math.max(...orders.map(o => o.id)) + 1 : 1;
    const datePart = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const randPart = Math.floor(100 + Math.random() * 900);
    const orderCode = `ESF-${datePart}-${randPart}`;

    const newOrder = {
        id: newId,
        orderCode: orderCode,
        createdAt: new Date().toISOString().replace('T', ' ').slice(0, 19),
        ...orderData
    };

    orders.unshift(newOrder);
    saveOrders(orders);

    // Simulated stock decrement
    if (newOrder.items && newOrder.items.length > 0) {
        const products = getProducts();
        newOrder.items.forEach(item => {
            const product = products.find(p => p.id === Number(item.productId));
            if (product) {
                product.stockQuantity = Math.max(0, product.stockQuantity - item.quantity);
                if (product.variants && item.variantId) {
                    const variant = product.variants.find(v => v.id === Number(item.variantId));
                    if (variant) {
                        variant.stock = Math.max(0, variant.stock - item.quantity);
                    }
                }
            }
        });
        saveProducts(products);
    }

    return newOrder;
}

// CART API
function getCart() {
    return getStorage(STORAGE_KEYS.CART, []);
}

function saveCart(cart) {
    setStorage(STORAGE_KEYS.CART, cart);
    updateCartBadge();
}

function addToCart(productId, variantId = null, quantity = 1) {
    const product = getProductById(productId);
    if (!product) return { success: false, message: 'Sản phẩm không tồn tại!' };

    let variant = null;
    let price = product.salePrice || product.price;
    let variantName = 'Tiêu chuẩn';

    if (product.variants && product.variants.length > 0) {
        if (variantId) {
            variant = product.variants.find(v => v.id === Number(variantId));
        } else {
            variant = product.variants[0];
        }
        if (variant) {
            price = variant.price;
            variantName = `${variant.versionName} - ${variant.colorName}`;
            if (variant.stock <= 0) {
                return { success: false, message: 'Biến thể này hiện đã tạm hết hàng!' };
            }
        }
    } else if (product.stockQuantity <= 0) {
        return { success: false, message: 'Mẫu xe này hiện đã tạm hết hàng!' };
    }

    const cart = getCart();
    const existingIndex = cart.findIndex(item =>
        item.productId === Number(productId) &&
        (variant ? item.variantId === variant.id : !item.variantId)
    );

    if (existingIndex > -1) {
        cart[existingIndex].quantity += Number(quantity);
    } else {
        cart.push({
            productId: product.id,
            productName: product.name,
            sku: variant ? variant.sku : product.sku,
            variantId: variant ? variant.id : null,
            variantName: variantName,
            price: price,
            image: product.image,
            quantity: Number(quantity)
        });
    }

    saveCart(cart);
    return { success: true, message: `Đã thêm ${product.name} vào giỏ hàng!` };
}

function updateCartQuantity(index, newQty) {
    const cart = getCart();
    if (cart[index]) {
        if (newQty <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity = Number(newQty);
        }
        saveCart(cart);
    }
}

function removeCartItem(index) {
    const cart = getCart();
    if (cart[index]) {
        cart.splice(index, 1);
        saveCart(cart);
    }
}

function clearCart() {
    setStorage(STORAGE_KEYS.CART, []);
    updateCartBadge();
}

function getCartCount() {
    const cart = getCart();
    return cart.reduce((total, item) => total + Number(item.quantity), 0);
}

function updateCartBadge() {
    const count = getCartCount();
    document.querySelectorAll('.cart-count-badge').forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
    });
}

// Global Toast Notification Helper
function showToast(message, type = 'success') {
    let container = document.getElementById('esfast-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'esfast-toast-container';
        container.style.position = 'fixed';
        container.style.top = '24px';
        container.style.right = '24px';
        container.style.zIndex = '99999';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '10px';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bg = type === 'success' ? '#10b981' : type === 'danger' ? '#ef4444' : '#0052cc';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle';

    toast.style.background = bg;
    toast.style.color = '#ffffff';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 8px 24px rgba(0,0,0,0.2)';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.gap = '10px';
    toast.style.fontSize = '0.95rem';
    toast.style.fontFamily = 'inherit';
    toast.style.transition = 'all 0.3s ease';
    toast.style.transform = 'translateX(50px)';
    toast.style.opacity = '0';
    toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;

    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(50px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// Run badge update on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
});
