const navbar = document.querySelector('.navbar');
const searchForm = document.querySelector('.search-form');

const menuBtn = document.querySelector('#menu-btn');
if(menuBtn) menuBtn.onclick = () =>{
    if(navbar) navbar.classList.toggle('active');
    if(searchForm) searchForm.classList.remove('active');
}

const searchBtn = document.querySelector('#search-btn');
if(searchBtn) searchBtn.onclick = () =>{
    if(searchForm) searchForm.classList.toggle('active');
    if(navbar) navbar.classList.remove('active');
}

const cartBtn = document.querySelector('#cart-btn');
if(cartBtn) cartBtn.onclick = () =>{
    const modal = document.getElementById('cart-modal');
    if(modal) modal.style.display = 'flex';
    if(navbar) navbar.classList.remove('active');
    if(searchForm) searchForm.classList.remove('active');
}

window.onscroll = () =>{
    if(navbar) navbar.classList.remove('active');
    if(searchForm) searchForm.classList.remove('active');
    const modal = document.getElementById('cart-modal'); if(modal) modal.style.display = 'none';
}

// close cart modal
const cartClose = document.getElementById('cart-close');
if(cartClose) cartClose.onclick = () => { const modal = document.getElementById('cart-modal'); if(modal) modal.style.display = 'none'; };