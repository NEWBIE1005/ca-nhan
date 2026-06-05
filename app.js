/* ==========================================================================
   GASTROFLOW APP LOGIC - DATA & STATE MANAGEMENT
   ========================================================================== */

// --- Global Application State ---
let state = {
    tables: [],
    menu: [],
    orders: [],
    invoices: [],
    activeTab: 'dashboard',
    selectedTableId: null,
    cart: {
        tableId: null,
        items: [] // { menuItemId, qty, note }
    }
};

// --- Default Data Constants ---
const DEFAULT_TABLES = [
    { id: 1, name: 'Bàn 01', seats: 2, status: 'empty', orderId: null },
    { id: 2, name: 'Bàn 02', seats: 2, status: 'empty', orderId: null },
    { id: 3, name: 'Bàn 03', seats: 4, status: 'empty', orderId: null },
    { id: 4, name: 'Bàn 04', seats: 4, status: 'empty', orderId: null },
    { id: 5, name: 'Bàn 05', seats: 4, status: 'empty', orderId: null },
    { id: 6, name: 'Bàn 06', seats: 6, status: 'empty', orderId: null },
    { id: 7, name: 'Bàn 07', seats: 6, status: 'empty', orderId: null },
    { id: 8, name: 'Bàn 08', seats: 8, status: 'empty', orderId: null },
    { id: 9, name: 'Bàn 09', seats: 8, status: 'empty', orderId: null },
    { id: 10, name: 'Bàn 10', seats: 10, status: 'empty', orderId: null },
    { id: 11, name: 'Bàn VIP 01', seats: 6, status: 'empty', orderId: null },
    { id: 12, name: 'Bàn VIP 02', seats: 12, status: 'empty', orderId: null }
];

const DEFAULT_MENU = [
    { id: 101, name: 'Gỏi Cuốn Tôm Thịt (3 chiếc)', category: 'khaivi', price: 65000, status: 'available', image: 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300' },
    { id: 102, name: 'Súp Hải Sản Tóc Tiên', category: 'khaivi', price: 75000, status: 'available', image: 'https://images.unsplash.com/photo-1547592180-85f173990554?w=300' },
    { id: 103, name: 'Bò Bít Tết Sốt Tiêu Đen Úc', category: 'monchinh', price: 245000, status: 'available', image: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=300' },
    { id: 104, name: 'Cơm Chiên Hải Sản Trứng Muối', category: 'monchinh', price: 135000, status: 'available', image: 'https://images.unsplash.com/photo-1603133872878-685f158659a5?w=300' },
    { id: 105, name: 'Gà Nướng Mật Ong Rừng Tây Bắc', category: 'monchinh', price: 185000, status: 'available', image: 'https://images.unsplash.com/photo-1598515214211-89d3e73ae83b?w=300' },
    { id: 106, name: 'Trà Đào Cam Sả Đá', category: 'douong', price: 45000, status: 'available', image: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300' },
    { id: 107, name: 'Cà Phê Muối GastroFlow', category: 'douong', price: 49000, status: 'available', image: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=300' },
    { id: 108, name: 'Bánh Mousse Chanh Leo', category: 'trangmieng', price: 55000, status: 'available', image: 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=300' },
    { id: 109, name: 'Chè Khúc Bạch Hạnh Nhân', category: 'trangmieng', price: 45000, status: 'available', image: 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=300' }
];

const DEFAULT_INVOICES = [
    {
        id: 'INV-060601',
        orderId: 991,
        tableId: 3,
        tableName: 'Bàn 03',
        items: [
            { menuItemId: 103, name: 'Bò Bít Tết Sốt Tiêu Đen Úc', qty: 2, price: 245000 },
            { menuItemId: 106, name: 'Trà Đào Cam Sả Đá', qty: 2, price: 45000 }
        ],
        subtotal: 580000,
        tax: 58000,
        total: 638000,
        timestamp: new Date(Date.now() - 3 * 3600 * 1000).toISOString(), // 3 hours ago
        paymentMethod: 'QR'
    },
    {
        id: 'INV-060602',
        orderId: 992,
        tableId: 5,
        tableName: 'Bàn 05',
        items: [
            { menuItemId: 104, name: 'Cơm Chiên Hải Sản Trứng Muối', qty: 1, price: 135000 },
            { menuItemId: 108, name: 'Bánh Mousse Chanh Leo', qty: 1, price: 55000 },
            { menuItemId: 107, name: 'Cà Phê Muối GastroFlow', qty: 1, price: 49000 }
        ],
        subtotal: 239000,
        tax: 23900,
        total: 262900,
        timestamp: new Date(Date.now() - 1 * 3600 * 1000).toISOString(), // 1 hour ago
        paymentMethod: 'Cash'
    }
];

// --- Initialization & LocalStorage Sync ---
function initApp() {
    loadStateFromStorage();
    setupEventListeners();
    startClock();
    
    // Initial Render
    switchTab(state.activeTab);
    updateGlobalStats();
    
    showToast('Hệ thống GastroFlow khởi tạo thành công!', 'success');
}

function loadStateFromStorage() {
    try {
        const storedTables = localStorage.getItem('gf_tables');
        const storedMenu = localStorage.getItem('gf_menu');
        const storedOrders = localStorage.getItem('gf_orders');
        const storedInvoices = localStorage.getItem('gf_invoices');
        const storedTheme = localStorage.getItem('gf_theme') || 'dark';

        state.tables = storedTables ? JSON.parse(storedTables) : [...DEFAULT_TABLES];
        state.menu = storedMenu ? JSON.parse(storedMenu) : [...DEFAULT_MENU];
        state.orders = storedOrders ? JSON.parse(storedOrders) : [];
        state.invoices = storedInvoices ? JSON.parse(storedInvoices) : [...DEFAULT_INVOICES];
        
        // Apply theme
        document.documentElement.setAttribute('data-theme', storedTheme);
        updateThemeToggleButton(storedTheme);
    } catch (e) {
        console.error('Lỗi khi khôi phục dữ liệu từ localStorage, sử dụng dữ liệu mặc định:', e);
        state.tables = [...DEFAULT_TABLES];
        state.menu = [...DEFAULT_MENU];
        state.orders = [];
        state.invoices = [...DEFAULT_INVOICES];
    }
}

function saveStateToStorage() {
    localStorage.setItem('gf_tables', JSON.stringify(state.tables));
    localStorage.setItem('gf_menu', JSON.stringify(state.menu));
    localStorage.setItem('gf_orders', JSON.stringify(state.orders));
    localStorage.setItem('gf_invoices', JSON.stringify(state.invoices));
}

// --- Utilities ---
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', 'đ');
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'fa-circle-check';
    if (type === 'info') icon = 'fa-circle-info';
    if (type === 'warning') icon = 'fa-triangle-exclamation';
    if (type === 'danger') icon = 'fa-circle-xmark';
    
    toast.innerHTML = `
        <i class="fa-solid ${icon}"></i>
        <div class="toast-message">${message}</div>
    `;
    
    container.appendChild(toast);
    
    // Smooth out animation
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function startClock() {
    const clockEl = document.getElementById('live-clock');
    const updateTime = () => {
        const now = new Date();
        const dateStr = now.toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const timeStr = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        clockEl.textContent = `${dateStr} | ${timeStr}`;
    };
    updateTime();
    setInterval(updateTime, 1000);
}

// --- Theme Toggle ---
function setupEventListeners() {
    // Tab switching
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', () => {
            const tabName = item.getAttribute('data-tab');
            switchTab(tabName);
        });
    });

    // Theme toggle
    document.getElementById('theme-toggle-btn').addEventListener('click', toggleTheme);

    // Global and fast searches
    document.getElementById('global-search').addEventListener('input', handleGlobalSearch);
    document.getElementById('pos-food-search').addEventListener('input', handlePOSFoodSearch);
    document.getElementById('menu-settings-search').addEventListener('input', handleMenuSettingsSearch);
    
    // POS Table selection change
    document.getElementById('cart-table-select').addEventListener('change', (e) => {
        const tableId = parseInt(e.target.value);
        if (tableId) {
            selectTableForPOS(tableId);
        }
    });

    // POS Filters
    document.querySelectorAll('#pos-category-tabs .category-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('#pos-category-tabs .category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderPOSMenu();
        });
    });

    // Table view filters
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderTablesGrid();
        });
    });

    // Payment method radio details
    document.querySelectorAll('.method-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.method-option').forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            const radio = option.querySelector('input');
            radio.checked = true;
            
            const qrMockup = document.getElementById('qr-mockup');
            if (radio.value === 'QR') {
                qrMockup.classList.add('active');
                // Dynamically modify QR URL if needed
                const totalText = document.getElementById('receipt-total').innerText.replace(/\D/g, '');
                const invId = document.getElementById('receipt-invoice-id').innerText;
                const qrImg = qrMockup.querySelector('img');
                qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=GASTROFLOW-PAYMENT-${invId}-${totalText}`;
            } else {
                qrMockup.classList.remove('active');
            }
        });
    });
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('gf_theme', newTheme);
    updateThemeToggleButton(newTheme);
    showToast(`Đã chuyển sang chế độ ${newTheme === 'dark' ? 'Tối' : 'Sáng'}`, 'info');
}

function updateThemeToggleButton(theme) {
    const textEl = document.querySelector('.theme-text');
    if (theme === 'dark') {
        textEl.textContent = 'Chế độ tối';
    } else {
        textEl.textContent = 'Chế độ sáng';
    }
}

// --- Navigation ---
function switchTab(tabName) {
    state.activeTab = tabName;
    
    // Update active nav link
    document.querySelectorAll('.nav-item').forEach(item => {
        if (item.getAttribute('data-tab') === tabName) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // Update Tab Panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    const targetPane = document.getElementById(`tab-${tabName}`);
    if (targetPane) targetPane.classList.add('active');

    // Update header Title
    const headerTitle = document.getElementById('current-tab-title');
    const titles = {
        dashboard: 'Tổng Quan Hệ Thống',
        tables: 'Sơ Đồ Bàn Ăn',
        pos: 'Màn Hình Gọi Món',
        orders: 'Điều Phối Bếp & Đơn Hàng Live',
        menu: 'Quản Lý Thực Đơn',
        history: 'Lịch Sử Hóa Đơn & Doanh Thu'
    };
    headerTitle.textContent = titles[tabName] || 'GastroFlow Bistro';

    // Call individual renders
    if (tabName === 'dashboard') {
        renderDashboard();
    } else if (tabName === 'tables') {
        renderTablesGrid();
    } else if (tabName === 'pos') {
        initPOSScreen();
    } else if (tabName === 'orders') {
        renderKitchenLive();
    } else if (tabName === 'menu') {
        renderMenuSettings();
    } else if (tabName === 'history') {
        renderInvoicesHistory();
    }
}

// --- Global Statistics Calculator ---
function updateGlobalStats() {
    // Badges update
    const activeTablesCount = state.tables.filter(t => t.status !== 'empty').length;
    document.getElementById('active-tables-badge').textContent = `${activeTablesCount}/${state.tables.length}`;
    
    const pendingOrdersCount = state.orders.filter(o => o.status === 'pending' || o.status === 'preparing').length;
    const badgeOrders = document.getElementById('pending-orders-badge');
    badgeOrders.textContent = pendingOrdersCount;
    if (pendingOrdersCount > 0) {
        badgeOrders.style.display = 'block';
    } else {
        badgeOrders.style.display = 'none';
    }

    // Revenue calculator (Today)
    const today = new Date().toDateString();
    const todayInvoices = state.invoices.filter(inv => new Date(inv.timestamp).toDateString() === today);
    const todayRevenue = todayInvoices.reduce((sum, inv) => sum + inv.total, 0);
    
    document.getElementById('header-today-revenue').textContent = formatCurrency(todayRevenue);
    
    // Update statistics dashboard card totals if on dashboard tab
    const statRev = document.getElementById('stat-revenue');
    if (statRev) statRev.textContent = formatCurrency(todayRevenue);

    const statTables = document.getElementById('stat-occupied-tables');
    if (statTables) {
        statTables.textContent = `${activeTablesCount} / ${state.tables.length}`;
        const pct = (activeTablesCount / state.tables.length) * 100;
        document.getElementById('stat-occupied-progress').style.width = `${pct}%`;
    }

    const statActive = document.getElementById('stat-active-orders');
    if (statActive) {
        const activeCount = state.orders.filter(o => o.status !== 'paid').length;
        statActive.textContent = `${activeCount} Đơn`;
    }

    const statServed = document.getElementById('stat-served-count');
    if (statServed) {
        const servedQty = state.orders
            .filter(o => o.status === 'served' || o.status === 'ready')
            .reduce((total, ord) => total + ord.items.reduce((sum, item) => sum + item.qty, 0), 0);
        statServed.textContent = `${servedQty} Món`;
    }
}

// --- TAB 1: DASHBOARD RENDER ---
function renderDashboard() {
    updateGlobalStats();
    
    // Mini grid render
    const miniGrid = document.getElementById('mini-table-grid');
    miniGrid.innerHTML = '';
    
    // Display up to 12 tables
    state.tables.slice(0, 12).forEach(table => {
        const cell = document.createElement('div');
        cell.className = `mini-table-cell ${table.status}`;
        
        let statusIcon = '<i class="fa-solid fa-check"></i>';
        if (table.status === 'serving') statusIcon = '<i class="fa-solid fa-mug-hot"></i>';
        if (table.status === 'reserved') statusIcon = '<i class="fa-solid fa-user-clock"></i>';
        
        cell.innerHTML = `
            <span>${table.name.replace('Bàn ', '')}</span>
            ${statusIcon}
        `;
        cell.onclick = () => {
            if (table.status === 'serving') {
                switchTab('pos');
                selectTableForPOS(table.id);
            } else {
                switchTab('tables');
            }
        };
        miniGrid.appendChild(cell);
    });

    // Popular items ranking calculation
    const salesCounter = {};
    state.invoices.forEach(inv => {
        inv.items.forEach(item => {
            if (!salesCounter[item.menuItemId]) {
                salesCounter[item.menuItemId] = { name: item.name, qty: 0, price: item.price };
            }
            salesCounter[item.menuItemId].qty += item.qty;
        });
    });

    const popularList = document.getElementById('popular-items-list');
    popularList.innerHTML = '';
    
    const sortedDishes = Object.keys(salesCounter)
        .map(id => ({ id: parseInt(id), ...salesCounter[id] }))
        .sort((a, b) => b.qty - a.qty)
        .slice(0, 4);

    if (sortedDishes.length === 0) {
        popularList.innerHTML = `<p class="text-muted text-center" style="font-size: 13px; padding: 20px 0;">Chưa có dữ liệu bán hàng hôm nay</p>`;
    } else {
        sortedDishes.forEach(dish => {
            const menuItem = state.menu.find(m => m.id === dish.id);
            const imgSrc = menuItem ? menuItem.image : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=100';
            
            const div = document.createElement('div');
            div.className = 'popular-item';
            div.innerHTML = `
                <img src="${imgSrc}" alt="${dish.name}">
                <div class="popular-item-info">
                    <h4>${dish.name}</h4>
                    <span>Đơn giá: ${formatCurrency(dish.price)}</span>
                </div>
                <div class="popular-item-sales">
                    <strong>${dish.qty} Đã bán</strong>
                    <span>+${formatCurrency(dish.qty * dish.price)}</span>
                </div>
            `;
            popularList.appendChild(div);
        });
    }

    // Recent invoices list render
    const recentList = document.getElementById('recent-invoices-list');
    recentList.innerHTML = '';
    
    const sortedInvoices = [...state.invoices]
        .sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp))
        .slice(0, 5);

    if (sortedInvoices.length === 0) {
        recentList.innerHTML = `<p class="text-muted text-center" style="font-size: 13px; padding: 20px 0;">Chưa có hóa đơn nào được lập hôm nay</p>`;
    } else {
        sortedInvoices.forEach(inv => {
            const time = new Date(inv.timestamp).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            const card = document.createElement('div');
            card.className = 'recent-invoice-card';
            card.innerHTML = `
                <div class="invoice-meta">
                    <h4>${inv.id} - ${inv.tableName}</h4>
                    <p>${time} | ${inv.items.length} món ăn</p>
                </div>
                <div class="invoice-amount">
                    <strong>${formatCurrency(inv.total)}</strong>
                    <span class="invoice-method-badge method-${inv.paymentMethod}">${inv.paymentMethod === 'Cash' ? 'Tiền mặt' : inv.paymentMethod === 'Card' ? 'Thẻ' : 'QR chuyển khoản'}</span>
                </div>
            `;
            recentList.appendChild(card);
        });
    }
}

// --- TAB 2: TABLES LIST RENDER ---
function renderTablesGrid() {
    const grid = document.getElementById('tables-grid');
    grid.innerHTML = '';
    
    const filterBtn = document.querySelector('.filter-btn.active');
    const filter = filterBtn ? filterBtn.getAttribute('data-filter') : 'all';

    // Counts updating
    document.getElementById('count-all-tables').textContent = state.tables.length;
    document.getElementById('count-empty-tables').textContent = state.tables.filter(t => t.status === 'empty').length;
    document.getElementById('count-serving-tables').textContent = state.tables.filter(t => t.status === 'serving').length;
    document.getElementById('count-reserved-tables').textContent = state.tables.filter(t => t.status === 'reserved').length;

    const filteredTables = state.tables.filter(table => {
        if (filter === 'all') return true;
        return table.status === filter;
    });

    filteredTables.forEach(table => {
        const card = document.createElement('div');
        card.className = `glass-card table-card status-${table.status}`;
        
        let subInfo = '';
        let actionButtons = '';
        
        if (table.status === 'empty') {
            subInfo = `<span class="seat-info"><i class="fa-solid fa-user-group"></i> ${table.seats} Ghế</span>`;
            actionButtons = `
                <button class="btn btn-sm btn-primary flex-grow" onclick="setTableStatus(${table.id}, 'serving', event)"><i class="fa-solid fa-mug-hot"></i> Mở Bàn</button>
                <button class="btn btn-sm btn-outline" onclick="setTableStatus(${table.id}, 'reserved', event)"><i class="fa-solid fa-user-clock"></i> Đặt Trước</button>
            `;
        } else if (table.status === 'serving') {
            const order = state.orders.find(o => o.tableId === table.id && o.status !== 'paid');
            const amount = order ? order.total : 0;
            subInfo = `
                <span class="seat-info"><i class="fa-solid fa-user-group"></i> ${table.seats} Ghế</span>
                <div class="order-amount-preview text-success">${formatCurrency(amount)}</div>
                <div class="time-occupied"><i class="fa-solid fa-clock"></i> Đang gọi món / dùng bữa</div>
            `;
            actionButtons = `
                <button class="btn btn-sm btn-primary flex-grow" onclick="openPOSTable(${table.id}, event)"><i class="fa-solid fa-cart-plus"></i> Gọi Món</button>
                <button class="btn btn-sm btn-success" onclick="checkoutTable(${table.id}, event)"><i class="fa-solid fa-cash-register"></i> Thanh Toán</button>
            `;
        } else if (table.status === 'reserved') {
            subInfo = `
                <span class="seat-info"><i class="fa-solid fa-user-group"></i> ${table.seats} Ghế</span>
                <div class="time-occupied text-warning"><i class="fa-solid fa-user-clock"></i> Khách đã hẹn trước</div>
            `;
            actionButtons = `
                <button class="btn btn-sm btn-primary flex-grow" onclick="setTableStatus(${table.id}, 'serving', event)"><i class="fa-solid fa-check"></i> Khách Đến</button>
                <button class="btn btn-sm btn-outline-danger" onclick="setTableStatus(${table.id}, 'empty', event)"><i class="fa-solid fa-xmark"></i> Hủy Đặt</button>
            `;
        }

        card.innerHTML = `
            <div class="table-card-header">
                <h3>${table.name}</h3>
                <span class="table-status-label">${table.status === 'empty' ? 'Trống' : table.status === 'serving' ? 'Phục vụ' : 'Đã đặt'}</span>
            </div>
            <div class="table-card-body">
                ${subInfo}
            </div>
            <div class="table-card-actions">
                ${actionButtons}
            </div>
        `;
        
        // Card click triggers primary action
        card.onclick = () => {
            if (table.status === 'serving') {
                openPOSTable(table.id);
            }
        };

        grid.appendChild(card);
    });
}

function setTableStatus(tableId, status, event) {
    if (event) event.stopPropagation(); // Stop card click
    
    const tableIndex = state.tables.findIndex(t => t.id === tableId);
    if (tableIndex === -1) return;

    state.tables[tableIndex].status = status;
    
    if (status === 'serving') {
        // Create matching active order if not exist
        let order = state.orders.find(o => o.tableId === tableId && o.status !== 'paid');
        if (!order) {
            order = {
                id: Date.now(),
                tableId: tableId,
                items: [],
                total: 0,
                status: 'pending',
                timestamp: new Date().toISOString()
            };
            state.orders.push(order);
        }
        state.tables[tableIndex].orderId = order.id;
        showToast(`Bàn ${state.tables[tableIndex].name} đã được mở phục vụ.`, 'success');
        // Redirect to POS
        switchTab('pos');
        selectTableForPOS(tableId);
    } else {
        state.tables[tableIndex].orderId = null;
        if (status === 'empty') {
            // Delete pending unpaid order if empty
            state.orders = state.orders.filter(o => !(o.tableId === tableId && o.status !== 'paid'));
            showToast(`Bàn ${state.tables[tableIndex].name} đã dọn dẹp sạch sẽ.`, 'info');
        } else {
            showToast(`Đã ghi nhận đặt trước cho Bàn ${state.tables[tableIndex].name}.`, 'warning');
        }
    }
    
    saveStateToStorage();
    renderTablesGrid();
    updateGlobalStats();
}

function openPOSTable(tableId, event) {
    if (event) event.stopPropagation();
    switchTab('pos');
    selectTableForPOS(tableId);
}

// --- TAB 3: POS SCREEN LOGIC ---
function initPOSScreen() {
    // 1. Load Table Selector Options
    const select = document.getElementById('cart-table-select');
    select.innerHTML = '<option value="">-- Chọn Bàn --</option>';
    
    state.tables.forEach(table => {
        // Render all tables, show serving status in name
        const prefix = table.status === 'serving' ? '● ' : table.status === 'reserved' ? '⏰ ' : '';
        const opt = document.createElement('option');
        opt.value = table.id;
        opt.textContent = `${prefix}${table.name} (${table.seats} ghế)`;
        
        if (state.cart.tableId === table.id) {
            opt.selected = true;
        }
        select.appendChild(opt);
    });

    // 2. Render POS menu items grid
    renderPOSMenu();
    
    // 3. Render Cart panel details
    renderCart();
}

function renderPOSMenu() {
    const grid = document.getElementById('pos-menu-grid');
    grid.innerHTML = '';

    const filterBtn = document.querySelector('#pos-category-tabs .category-btn.active');
    const categoryFilter = filterBtn ? filterBtn.getAttribute('data-category') : 'all';
    const searchText = document.getElementById('pos-food-search').value.toLowerCase().trim();

    const filteredMenu = state.menu.filter(item => {
        const matchesCategory = (categoryFilter === 'all' || item.category === categoryFilter);
        const matchesSearch = item.name.toLowerCase().includes(searchText);
        return matchesCategory && matchesSearch;
    });

    if (filteredMenu.length === 0) {
        grid.innerHTML = `<div class="text-center text-muted" style="grid-column: 1/-1; padding: 40px 0;">Không tìm thấy món ăn nào khớp</div>`;
        return;
    }

    filteredMenu.forEach(item => {
        const card = document.createElement('div');
        card.className = `food-card ${item.status === 'out_of_stock' ? 'out-of-stock' : ''}`;
        
        card.innerHTML = `
            <div class="food-card-img-wrap">
                <img src="${item.image}" alt="${item.name}">
                <span class="food-card-category">${item.category === 'khaivi' ? 'Khai Vị' : item.category === 'monchinh' ? 'Món Chính' : item.category === 'douong' ? 'Đồ Uống' : 'Tráng Miệng'}</span>
            </div>
            <div class="food-card-body">
                <h4 class="food-card-title">${item.name}</h4>
                <div class="food-card-footer">
                    <span class="food-card-price">${formatCurrency(item.price)}</span>
                    <button class="food-card-add-btn" onclick="addFoodToCart(${item.id}, event)"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
        `;
        
        card.onclick = (e) => {
            if (item.status !== 'out_of_stock') {
                addFoodToCart(item.id, e);
            }
        };

        grid.appendChild(card);
    });
}

function selectTableForPOS(tableId) {
    state.cart.tableId = tableId;
    
    // Check if table is currently "empty" or "reserved". If so, auto change to "serving"
    const tableIndex = state.tables.findIndex(t => t.id === tableId);
    if (tableIndex !== -1 && state.tables[tableIndex].status !== 'serving') {
        state.tables[tableIndex].status = 'serving';
        
        // Check or create order
        let order = state.orders.find(o => o.tableId === tableId && o.status !== 'paid');
        if (!order) {
            order = {
                id: Date.now(),
                tableId: tableId,
                items: [],
                total: 0,
                status: 'pending',
                timestamp: new Date().toISOString()
            };
            state.orders.push(order);
        }
        state.tables[tableIndex].orderId = order.id;
        saveStateToStorage();
        updateGlobalStats();
    }

    // Load table's current order items into cart
    const activeOrder = state.orders.find(o => o.tableId === tableId && o.status !== 'paid');
    if (activeOrder) {
        state.cart.items = activeOrder.items.map(item => ({...item})); // Deep copy
    } else {
        state.cart.items = [];
    }

    // Update select element in UI
    const select = document.getElementById('cart-table-select');
    if (select) select.value = tableId;
    
    renderCart();
}

function addFoodToCart(menuItemId, event) {
    if (event) event.stopPropagation();

    if (!state.cart.tableId) {
        showToast('Vui lòng chọn bàn ăn trước khi thêm món!', 'warning');
        return;
    }

    const menuItem = state.menu.find(m => m.id === menuItemId);
    if (!menuItem) return;

    const existingCartItem = state.cart.items.find(item => item.menuItemId === menuItemId);
    if (existingCartItem) {
        existingCartItem.qty += 1;
    } else {
        state.cart.items.push({
            menuItemId: menuItemId,
            qty: 1,
            note: ''
        });
    }

    showToast(`Đã thêm ${menuItem.name} vào giỏ món`, 'success');
    renderCart();
    autoSaveCartToOrder(); // Save POS status instantly
}

function adjustCartItemQty(menuItemId, offset) {
    const itemIndex = state.cart.items.findIndex(item => item.menuItemId === menuItemId);
    if (itemIndex === -1) return;

    state.cart.items[itemIndex].qty += offset;
    
    if (state.cart.items[itemIndex].qty <= 0) {
        state.cart.items.splice(itemIndex, 1);
    }
    
    renderCart();
    autoSaveCartToOrder();
}

function updateCartItemNote(menuItemId, noteText) {
    const item = state.cart.items.find(item => item.menuItemId === menuItemId);
    if (item) {
        item.note = noteText;
        autoSaveCartToOrder();
    }
}

function autoSaveCartToOrder() {
    if (!state.cart.tableId) return;

    const activeOrderIndex = state.orders.findIndex(o => o.tableId === state.cart.tableId && o.status !== 'paid');
    if (activeOrderIndex !== -1) {
        state.orders[activeOrderIndex].items = state.cart.items.map(item => ({...item}));
        state.orders[activeOrderIndex].total = calculateCartTotal().total;
        saveStateToStorage();
        updateGlobalStats();
    }
}

function calculateCartTotal() {
    let subtotal = 0;
    state.cart.items.forEach(item => {
        const dish = state.menu.find(m => m.id === item.menuItemId);
        if (dish) {
            subtotal += dish.price * item.qty;
        }
    });
    const tax = Math.round(subtotal * 0.1);
    const total = subtotal + tax;
    return { subtotal, tax, total };
}

function renderCart() {
    const listWrapper = document.getElementById('cart-items-list');
    listWrapper.innerHTML = '';

    if (!state.cart.tableId) {
        listWrapper.innerHTML = `
            <div class="cart-empty-state">
                <i class="fa-solid fa-chair"></i>
                <p>Bàn chưa được chọn</p>
                <span>Vui lòng click chọn sơ đồ bàn hoặc chọn mã bàn ở trên để tiến hành order.</span>
            </div>
        `;
        document.getElementById('cart-subtotal').textContent = '0 đ';
        document.getElementById('cart-tax').textContent = '0 đ';
        document.getElementById('cart-total').textContent = '0 đ';
        return;
    }

    if (state.cart.items.length === 0) {
        listWrapper.innerHTML = `
            <div class="cart-empty-state">
                <i class="fa-solid fa-basket-shopping"></i>
                <p>Chưa có món nào được chọn</p>
                <span>Nhấp chọn món ăn trong thực đơn để thêm vào bàn đang chọn.</span>
            </div>
        `;
        document.getElementById('cart-subtotal').textContent = '0 đ';
        document.getElementById('cart-tax').textContent = '0 đ';
        document.getElementById('cart-total').textContent = '0 đ';
        return;
    }

    state.cart.items.forEach(item => {
        const dish = state.menu.find(m => m.id === item.menuItemId);
        if (!dish) return;

        const cartItemEl = document.createElement('div');
        cartItemEl.className = 'cart-item';
        cartItemEl.innerHTML = `
            <div class="cart-item-row">
                <span class="cart-item-name">${dish.name}</span>
                <span class="cart-item-price">${formatCurrency(dish.price * item.qty)}</span>
            </div>
            <div class="cart-item-row">
                <input type="text" class="cart-item-note" placeholder="Thêm ghi chú nấu... (ít cay, nhiều hành...)" value="${item.note}" onchange="updateCartItemNote(${dish.id}, this.value)">
                <div class="cart-item-controls">
                    <button class="qty-btn" onclick="adjustCartItemQty(${dish.id}, -1)"><i class="fa-solid fa-minus"></i></button>
                    <span class="qty-val">${item.qty}</span>
                    <button class="qty-btn" onclick="adjustCartItemQty(${dish.id}, 1)"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
        `;
        listWrapper.appendChild(cartItemEl);
    });

    const totals = calculateCartTotal();
    document.getElementById('cart-subtotal').textContent = formatCurrency(totals.subtotal);
    document.getElementById('cart-tax').textContent = formatCurrency(totals.tax);
    document.getElementById('cart-total').textContent = formatCurrency(totals.total);
}

function clearCart() {
    if (!state.cart.tableId) return;
    
    if (confirm('Bạn có chắc chắn muốn hủy toàn bộ giỏ món của bàn này không?')) {
        state.cart.items = [];
        renderCart();
        autoSaveCartToOrder();
        showToast('Giỏ món đã được làm sạch', 'info');
    }
}

function submitCartToKitchen() {
    if (!state.cart.tableId) {
        showToast('Vui lòng chọn bàn ăn trước!', 'warning');
        return;
    }
    if (state.cart.items.length === 0) {
        showToast('Giỏ hàng trống! Hãy chọn món trước.', 'warning');
        return;
    }

    // Save cart state
    autoSaveCartToOrder();
    
    // Trigger notification
    const table = state.tables.find(t => t.id === state.cart.tableId);
    showToast(`Đã gửi yêu cầu chế biến món ăn của ${table ? table.name : 'bàn'} xuống nhà bếp!`, 'success');
    
    // Switch to kitchen list
    setTimeout(() => {
        switchTab('orders');
    }, 500);
}

// --- TAB 4: LIVE KITCHEN & ORDER COORDINATOR ---
function renderKitchenLive() {
    const listPending = document.getElementById('kitchen-pending-orders');
    const listPreparing = document.getElementById('kitchen-preparing-orders');
    const listReady = document.getElementById('kitchen-ready-orders');
    const listServed = document.getElementById('kitchen-served-orders');

    // Reset columns
    listPending.innerHTML = '';
    listPreparing.innerHTML = '';
    listReady.innerHTML = '';
    listServed.innerHTML = '';

    let cPending = 0, cPreparing = 0, cReady = 0, cServed = 0;

    state.orders.forEach(order => {
        if (order.status === 'paid') return; // Hide completed sales
        
        const table = state.tables.find(t => t.id === order.tableId);
        const tableName = table ? table.name : `Bàn #${order.tableId}`;
        
        // Calculate minutes elapsed
        const elapsedMin = Math.round((Date.now() - new Date(order.timestamp).getTime()) / 60000);
        
        // Build items HTML
        let itemsHtml = '';
        order.items.forEach(item => {
            const menuItem = state.menu.find(m => m.id === item.menuItemId);
            if (!menuItem) return;
            
            itemsHtml += `
                <li>
                    <strong>${item.qty}x</strong> ${menuItem.name}
                    ${item.note ? `<p class="note">${item.note}</p>` : ''}
                </li>
            `;
        });

        if (order.items.length === 0) return; // Skip empty orders

        const card = document.createElement('div');
        card.className = 'kitchen-order-card';
        
        let actionBtn = '';
        if (order.status === 'pending') {
            cPending++;
            actionBtn = `<button class="btn btn-sm btn-primary btn-block" onclick="updateOrderStatus(${order.id}, 'preparing')"><i class="fa-solid fa-fire"></i> Nhận Chế Biến</button>`;
        } else if (order.status === 'preparing') {
            cPreparing++;
            actionBtn = `<button class="btn btn-sm btn-success btn-block" onclick="updateOrderStatus(${order.id}, 'ready')"><i class="fa-solid fa-check"></i> Hoàn Thành Món</button>`;
        } else if (order.status === 'ready') {
            cReady++;
            actionBtn = `<button class="btn btn-sm btn-info btn-block" onclick="updateOrderStatus(${order.id}, 'served')"><i class="fa-solid fa-bell"></i> Đã Bưng Lên</button>`;
        } else if (order.status === 'served') {
            cServed++;
            actionBtn = `<button class="btn btn-sm btn-success btn-block" onclick="triggerCheckoutForOrder(${order.id})"><i class="fa-solid fa-cash-register"></i> Thanh Toán Bill</button>`;
        }

        card.innerHTML = `
            <div class="kitchen-order-meta">
                <span>${tableName}</span>
                <span>${elapsedMin} phút trước</span>
            </div>
            <ul class="kitchen-order-items">
                ${itemsHtml}
            </ul>
            <div class="kitchen-card-actions">
                ${actionBtn}
            </div>
        `;

        if (order.status === 'pending') listPending.appendChild(card);
        else if (order.status === 'preparing') listPreparing.appendChild(card);
        else if (order.status === 'ready') listReady.appendChild(card);
        else if (order.status === 'served') listServed.appendChild(card);
    });

    // Update numbers
    document.getElementById('count-pending-orders').textContent = cPending;
    document.getElementById('count-preparing-orders').textContent = cPreparing;
    document.getElementById('count-ready-orders').textContent = cReady;
    document.getElementById('count-served-orders').textContent = cServed;

    // Show empty placeholder inside columns if 0 items
    setupKitchenPlaceholders(listPending, cPending);
    setupKitchenPlaceholders(listPreparing, cPreparing);
    setupKitchenPlaceholders(listReady, cReady);
    setupKitchenPlaceholders(listServed, cServed);
}

function setupKitchenPlaceholders(el, count) {
    if (count === 0) {
        el.innerHTML = `
            <div class="text-center text-muted" style="padding: 40px 0; font-size: 12px; border: 1px dashed var(--border-color); border-radius: 12px;">
                Không có đơn hàng
            </div>
        `;
    }
}

function updateOrderStatus(orderId, newStatus) {
    const orderIndex = state.orders.findIndex(o => o.id === orderId);
    if (orderIndex === -1) return;

    state.orders[orderIndex].status = newStatus;
    
    // Status specific updates
    if (newStatus === 'preparing') {
        showToast('Đã chuyển đơn hàng vào nhà bếp chế biến.', 'info');
    } else if (newStatus === 'ready') {
        showToast('Món ăn đã sẵn sàng phục vụ tại quầy!', 'success');
    } else if (newStatus === 'served') {
        showToast('Đã bàn giao món ăn đầy đủ cho khách hàng.', 'success');
    }
    
    saveStateToStorage();
    renderKitchenLive();
    updateGlobalStats();
}

// --- TAB 5: MENU MANAGEMENT & CRUD ---
function renderMenuSettings() {
    const tbody = document.getElementById('menu-settings-tbody');
    tbody.innerHTML = '';
    
    const searchText = document.getElementById('menu-settings-search').value.toLowerCase().trim();
    
    const filteredMenu = state.menu.filter(item => {
        return item.name.toLowerCase().includes(searchText);
    });

    if (filteredMenu.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Không tìm thấy món ăn nào</td></tr>`;
        return;
    }

    filteredMenu.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="menu-td-img"><img src="${item.image}" alt="${item.name}"></td>
            <td><strong>${item.name}</strong></td>
            <td><span class="category-text">${item.category === 'khaivi' ? 'Khai Vị' : item.category === 'monchinh' ? 'Món Chính' : item.category === 'douong' ? 'Đồ Uống' : 'Tráng Miệng'}</span></td>
            <td>${formatCurrency(item.price)}</td>
            <td><span class="badge-status ${item.status}">${item.status === 'available' ? 'Còn Món' : 'Hết Món'}</span></td>
            <td>
                <button class="btn btn-sm btn-outline" style="margin-right: 4px;" onclick="openEditMenuModal(${item.id})"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(${item.id})"><i class="fa-solid fa-trash-can"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function openMenuModal() {
    document.getElementById('menu-modal-title').textContent = 'Thêm Món Mới Vào Thực Đơn';
    document.getElementById('menu-item-id').value = '';
    document.getElementById('menu-item-form').reset();
    document.getElementById('menu-item-modal').classList.add('active');
}

function openEditMenuModal(id) {
    const item = state.menu.find(m => m.id === id);
    if (!item) return;

    document.getElementById('menu-modal-title').textContent = 'Chỉnh Sửa Món Ăn';
    document.getElementById('menu-item-id').value = item.id;
    document.getElementById('menu-item-name').value = item.name;
    document.getElementById('menu-item-price').value = item.price;
    document.getElementById('menu-item-category').value = item.category;
    document.getElementById('menu-item-image').value = item.image;
    document.getElementById('menu-item-status').value = item.status;
    
    document.getElementById('menu-item-modal').classList.add('active');
}

function closeMenuModal() {
    document.getElementById('menu-item-modal').classList.remove('active');
}

function saveMenuItem(event) {
    event.preventDefault();

    const id = document.getElementById('menu-item-id').value;
    const name = document.getElementById('menu-item-name').value.trim();
    const price = parseInt(document.getElementById('menu-item-price').value);
    const category = document.getElementById('menu-item-category').value;
    let image = document.getElementById('menu-item-image').value.trim();
    const status = document.getElementById('menu-item-status').value;

    if (!name || isNaN(price)) {
        showToast('Vui lòng nhập tên món và giá tiền hợp lệ', 'danger');
        return;
    }

    if (!image) {
        image = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300'; // Fallback
    }

    if (id) {
        // Edit Mode
        const itemIdx = state.menu.findIndex(m => m.id === parseInt(id));
        if (itemIdx !== -1) {
            state.menu[itemIdx] = { id: parseInt(id), name, price, category, image, status };
            showToast('Đã cập nhật món ăn thành công', 'success');
        }
    } else {
        // Create Mode
        const newId = Date.now();
        state.menu.push({ id: newId, name, price, category, image, status });
        showToast('Đã thêm món ăn mới vào thực đơn', 'success');
    }

    saveStateToStorage();
    closeMenuModal();
    renderMenuSettings();
}

function deleteMenuItem(id) {
    if (confirm('Bạn có chắc chắn muốn xóa món này khỏi thực đơn?')) {
        state.menu = state.menu.filter(m => m.id !== id);
        saveStateToStorage();
        renderMenuSettings();
        showToast('Đã xóa món ăn khỏi thực đơn', 'info');
    }
}

// --- ADD NEW TABLE MODAL LOGIC ---
function openAddTableModal() {
    document.getElementById('table-form').reset();
    document.getElementById('table-modal').classList.add('active');
}

function closeTableModal() {
    document.getElementById('table-modal').classList.remove('active');
}

function saveNewTable(event) {
    event.preventDefault();
    const tableName = document.getElementById('new-table-name').value.trim();
    const seats = parseInt(document.getElementById('new-table-seats').value);

    if (!tableName || isNaN(seats)) {
        showToast('Tên bàn và số ghế không hợp lệ!', 'danger');
        return;
    }

    const newTable = {
        id: Date.now(),
        name: tableName,
        seats: seats,
        status: 'empty',
        orderId: null
    };

    state.tables.push(newTable);
    saveStateToStorage();
    closeTableModal();
    renderTablesGrid();
    updateGlobalStats();
    showToast(`Đã thêm ${tableName} thành công.`, 'success');
}

// --- CHECKOUT / PAYMENT MODAL PROCESS ---
let currentCheckoutOrder = null;

function triggerCheckout() {
    if (!state.cart.tableId) {
        showToast('Vui lòng chọn bàn cần thanh toán', 'warning');
        return;
    }
    
    const order = state.orders.find(o => o.tableId === state.cart.tableId && o.status !== 'paid');
    if (!order || order.items.length === 0) {
        showToast('Bàn này chưa có món ăn nào được gọi!', 'warning');
        return;
    }

    openCheckoutModal(order);
}

function checkoutTable(tableId, event) {
    if (event) event.stopPropagation();
    
    const order = state.orders.find(o => o.tableId === tableId && o.status !== 'paid');
    if (!order || order.items.length === 0) {
        showToast('Bàn này chưa gọi món ăn!', 'warning');
        return;
    }

    openCheckoutModal(order);
}

function triggerCheckoutForOrder(orderId) {
    const order = state.orders.find(o => o.id === orderId);
    if (!order) return;
    openCheckoutModal(order);
}

function openCheckoutModal(order) {
    currentCheckoutOrder = order;
    const table = state.tables.find(t => t.id === order.tableId);
    const tableName = table ? table.name : `Bàn #${order.tableId}`;

    // Fill Modal Data
    const invId = `INV-${new Date().toISOString().slice(2,10).replace(/-/g,'')}${String(order.id).slice(-4)}`;
    document.getElementById('receipt-invoice-id').textContent = invId;
    document.getElementById('receipt-time').textContent = new Date().toLocaleString('vi-VN');
    document.getElementById('receipt-table').textContent = tableName;

    // Fill list of items
    const listWrapper = document.getElementById('receipt-items-list');
    listWrapper.innerHTML = '';
    
    let subtotal = 0;
    order.items.forEach(item => {
        const dish = state.menu.find(m => m.id === item.menuItemId);
        if (!dish) return;
        
        subtotal += dish.price * item.qty;
        
        const row = document.createElement('div');
        row.className = 'receipt-item-row-print';
        row.innerHTML = `
            <div class="receipt-item-name-qty">
                <span>${dish.name}</span>
                <span class="receipt-item-qty">x${item.qty}</span>
            </div>
            <span>${formatCurrency(dish.price * item.qty)}</span>
        `;
        listWrapper.appendChild(row);
    });

    const tax = Math.round(subtotal * 0.1);
    const total = subtotal + tax;

    document.getElementById('receipt-subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('receipt-tax').textContent = formatCurrency(tax);
    document.getElementById('receipt-total').textContent = formatCurrency(total);

    // Default payment method reset to Cash
    document.querySelectorAll('.method-option').forEach(o => o.classList.remove('active'));
    const defaultOption = document.querySelector('.method-option[data-method="Cash"]');
    defaultOption.classList.add('active');
    defaultOption.querySelector('input').checked = true;
    document.getElementById('qr-mockup').classList.remove('active');

    // Show modal
    document.getElementById('checkout-modal').classList.add('active');
}

function closeCheckoutModal() {
    document.getElementById('checkout-modal').classList.remove('active');
    currentCheckoutOrder = null;
}

function completePaymentProcess() {
    if (!currentCheckoutOrder) return;
    
    const activeMethod = document.querySelector('input[name="pay-method"]:checked').value;
    
    const table = state.tables.find(t => t.id === currentCheckoutOrder.tableId);
    const tableName = table ? table.name : `Bàn #${currentCheckoutOrder.tableId}`;
    
    const totals = calculateOrderTotal(currentCheckoutOrder);
    const invoiceId = document.getElementById('receipt-invoice-id').textContent;

    // Map cart item structure to Invoice items
    const invoiceItems = currentCheckoutOrder.items.map(item => {
        const dish = state.menu.find(m => m.id === item.menuItemId);
        return {
            menuItemId: item.menuItemId,
            name: dish ? dish.name : 'Món ăn không rõ',
            qty: item.qty,
            price: dish ? dish.price : 0
        };
    });

    // Create Invoice Record
    const newInvoice = {
        id: invoiceId,
        orderId: currentCheckoutOrder.id,
        tableId: currentCheckoutOrder.tableId,
        tableName: tableName,
        items: invoiceItems,
        subtotal: totals.subtotal,
        tax: totals.tax,
        total: totals.total,
        timestamp: new Date().toISOString(),
        paymentMethod: activeMethod
    };

    state.invoices.push(newInvoice);

    // Clear and Update Table & Order state
    const tableIdx = state.tables.findIndex(t => t.id === currentCheckoutOrder.tableId);
    if (tableIdx !== -1) {
        state.tables[tableIdx].status = 'empty';
        state.tables[tableIdx].orderId = null;
    }

    // Set order status to paid
    const orderIdx = state.orders.findIndex(o => o.id === currentCheckoutOrder.id);
    if (orderIdx !== -1) {
        state.orders[orderIdx].status = 'paid';
    }

    // Reset current POS cart if it belonged to this table
    if (state.cart.tableId === currentCheckoutOrder.tableId) {
        state.cart.tableId = null;
        state.cart.items = [];
    }

    saveStateToStorage();
    closeCheckoutModal();
    
    // Switch to history or keep current
    showToast(`Đã thanh toán hóa đơn ${invoiceId} thành công!`, 'success');
    
    // Auto refresh active tab
    switchTab(state.activeTab);
    updateGlobalStats();
}

function calculateOrderTotal(order) {
    let subtotal = 0;
    order.items.forEach(item => {
        const dish = state.menu.find(m => m.id === item.menuItemId);
        if (dish) subtotal += dish.price * item.qty;
    });
    const tax = Math.round(subtotal * 0.1);
    const total = subtotal + tax;
    return { subtotal, tax, total };
}

// --- TAB 6: INVOICES HISTORY & DATE FILTER ---
function renderInvoicesHistory() {
    const tbody = document.getElementById('invoices-tbody');
    tbody.innerHTML = '';
    
    const filterFrom = document.getElementById('filter-date-from').value;
    const filterTo = document.getElementById('filter-date-to').value;

    let filteredInvoices = [...state.invoices];
    
    if (filterFrom) {
        const fromDate = new Date(filterFrom);
        fromDate.setHours(0, 0, 0, 0);
        filteredInvoices = filteredInvoices.filter(inv => new Date(inv.timestamp) >= fromDate);
    }
    
    if (filterTo) {
        const toDate = new Date(filterTo);
        toDate.setHours(23, 59, 59, 999);
        filteredInvoices = filteredInvoices.filter(inv => new Date(inv.timestamp) <= toDate);
    }

    // Sort descending by date
    filteredInvoices.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

    let historyTotal = 0;

    if (filteredInvoices.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Không có dữ liệu hóa đơn trong kỳ chọn</td></tr>`;
    } else {
        filteredInvoices.forEach(inv => {
            historyTotal += inv.total;
            const dateStr = new Date(inv.timestamp).toLocaleDateString('vi-VN');
            const timeStr = new Date(inv.timestamp).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            
            const itemsSummary = inv.items.map(i => `${i.name} (x${i.qty})`).join(', ');
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${inv.id}</strong></td>
                <td>${dateStr} ${timeStr}</td>
                <td>${inv.tableName}</td>
                <td style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${itemsSummary}">${itemsSummary}</td>
                <td class="text-success"><strong>${formatCurrency(inv.total)}</strong></td>
                <td><span class="invoice-method-badge method-${inv.paymentMethod}">${inv.paymentMethod === 'Cash' ? 'Tiền mặt' : inv.paymentMethod === 'Card' ? 'Thẻ' : 'QR'}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="reprintInvoice('${inv.id}')"><i class="fa-solid fa-print"></i> Xem Lại</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    document.getElementById('filtered-total-revenue').textContent = formatCurrency(historyTotal);
}

function filterInvoices() {
    renderInvoicesHistory();
    showToast('Đã áp dụng bộ lọc hóa đơn', 'info');
}

function resetInvoiceFilters() {
    document.getElementById('filter-date-from').value = '';
    document.getElementById('filter-date-to').value = '';
    renderInvoicesHistory();
}

function reprintInvoice(invoiceId) {
    const inv = state.invoices.find(i => i.id === invoiceId);
    if (!inv) return;

    // Open Checkout Modal in Read-only view
    // Create matching fake order object to feed the printer modal
    const mockOrder = {
        id: inv.orderId,
        tableId: inv.tableId,
        items: inv.items.map(item => ({
            menuItemId: item.menuItemId,
            qty: item.qty,
            note: ''
        })),
        total: inv.total,
        status: 'paid'
    };

    openCheckoutModal(mockOrder);
    
    // Override standard modal title details
    document.getElementById('receipt-invoice-id').textContent = inv.id;
    document.getElementById('receipt-time').textContent = new Date(inv.timestamp).toLocaleString('vi-VN');
    
    // Toggle active payment method
    document.querySelectorAll('.method-option').forEach(o => {
        const radio = o.querySelector('input');
        if (radio.value === inv.paymentMethod) {
            o.classList.add('active');
            radio.checked = true;
            if (inv.paymentMethod === 'QR') {
                const qrMockup = document.getElementById('qr-mockup');
                qrMockup.classList.add('active');
                const qrImg = qrMockup.querySelector('img');
                qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=GASTROFLOW-PAYMENT-${inv.id}-${inv.total}`;
            }
        } else {
            o.classList.remove('active');
        }
    });
    
    // Disable edit capabilities on reprinted bill
    const completeBtn = document.querySelector('.modal-footer .btn-primary');
    completeBtn.innerHTML = '<i class="fa-solid fa-print"></i> In Lại Hóa Đơn';
    completeBtn.onclick = () => {
        window.print();
        closeCheckoutModal();
        showToast('Yêu cầu in hóa đơn đã được gửi!', 'success');
    };
}

// --- SEAMLESS REALTIME SEARCHES ---
function handleGlobalSearch() {
    const query = document.getElementById('global-search').value.toLowerCase().trim();
    
    if (state.activeTab === 'tables') {
        const cards = document.querySelectorAll('.table-card');
        cards.forEach(card => {
            const tableName = card.querySelector('h3').innerText.toLowerCase();
            if (tableName.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    } else if (state.activeTab === 'pos') {
        document.getElementById('pos-food-search').value = query;
        renderPOSMenu();
    } else if (state.activeTab === 'menu') {
        document.getElementById('menu-settings-search').value = query;
        renderMenuSettings();
    }
}

function handlePOSFoodSearch() {
    renderPOSMenu();
}

function handleMenuSettingsSearch() {
    renderMenuSettings();
}

// --- BOOTSTRAP APP ON LOAD ---
window.onload = initApp;
