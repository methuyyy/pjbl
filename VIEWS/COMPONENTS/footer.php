<style>
  /* ===== FOOTER ===== */
  footer {
    background: #4a1a24; /* primary-dark */
    color: rgba(255,255,255,0.75);
    padding: 52px 60px 30px;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1.4fr;
    gap: 48px;
    margin-bottom: 40px;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
  }

  .footer-brand .brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }

  .footer-brand .brand-logo .logo-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .footer-brand .brand-logo .logo-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .footer-brand .brand-logo span {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    color: white;
    font-weight: 700;
  }

  .footer-brand p {
    font-size: 0.83rem;
    line-height: 1.7;
    color: rgba(255,255,255,0.65);
    max-width: 240px;
  }

  .footer-col h4 {
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 16px;
  }

  .footer-col ul {
    list-style: none !important;
    text-decoration: none !important;
    padding: 0;
    margin: 0;
  }

  .footer-col ul li {
    list-style: none !important;
    margin-bottom: 8px;
  }

  .footer-col ul li a {
    font-size: 0.83rem;
    color: rgba(255,255,255,0.65);
    transition: color 0.2s;
  }

  .footer-col ul li a:hover { color: white; }

  .footer-col .contact-detail {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.5;
  }

  .footer-col .contact-detail svg {
    width: 14px;
    height: 14px;
    stroke: rgba(255,255,255,0.5);
    fill: none;
    stroke-width: 2;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.12);
    padding-top: 22px;
    text-align: center;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.4);
  }

  @media (max-width: 1024px) {
    footer { padding: 44px 32px 24px; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
  }

  @media (max-width: 768px) {
    .footer-grid { grid-template-columns: 1fr; gap: 28px; }
    footer { padding: 36px 20px 20px; }
  }
</style>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="brand-logo">
        <div class="logo-icon">
          <img src="../../images/logobg.png" alt="Logo Pawarti">
        </div>
        <span>Pawarti</span>
      </div>
      <p>Pawarti memperkenalkan kekayaan budaya Jawa melalui berbagai event seni yang inspiratif dan bermakna.</p>
    </div>

    <div class="footer-col">
      <h4>Kategori</h4>
      <ul>
        <li><a href="#">Pertunjukan Seni</a></li>
        <li><a href="#">Workshop Budaya</a></li>
        <li><a href="#">Musik Tradisional</a></li>
        <li><a href="#">Upacara Adat</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Kontak</h4>
      <div class="contact-detail">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <span>info@pawarti.budayajawa@gmail.com</span>
      </div>
      <div class="contact-detail">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19 19.45 19.45 0 0 1 5 12.18 19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91A16 16 0 0 0 14.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span>Telepon: 0800-5766-2459</span>
      </div>
      <div class="contact-detail">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <span>Jl Pemuda no.22 Kaum / Bon Kijon, Kota Malang Jawa Timur</span>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2024 Pawarti. Semua hak cipta dilindungi.</p>
  </div>
</footer>
