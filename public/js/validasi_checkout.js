// Fungsi utama untuk validasi form
function validateForm() {
  // Tandai bahwa form valid di awal
  let isValid = true;

  // 1. VALIDASI NAMA DEPAN
  const firstName = document.getElementById("firstName").value.trim();
  if (firstName === "") {
    // Jika nama depan kosong, tampilkan error
    showError("firstName", "Nama Depan harus diisi");
    isValid = false;
  } else {
    // Jika nama depan diisi, hapus error
    removeError("firstName");
  }

  // 2. VALIDASI NAMA BELAKANG
  const lastName = document.getElementById("lastName").value.trim();
  if (lastName === "") {
    showError("lastName", "Nama Belakang harus diisi");
    isValid = false;
  } else {
    removeError("lastName");
  }

  // 3. VALIDASI ALAMAT
  const streetAddress = document.getElementById("streetAddress").value.trim();
  if (streetAddress === "") {
    showError("streetAddress", "Alamat harus diisi");
    isValid = false;
  } else {
    removeError("streetAddress");
  }

  // 4. VALIDASI KOTA
  const city = document.getElementById("city").value.trim();
  if (city === "") {
    showError("city", "Kota harus diisi");
    isValid = false;
  } else {
    removeError("city");
  }

  // 5. VALIDASI PROVINSI
  const state = document.getElementById("state").value;
  if (state === "") {
    showError("state", "Provinsi harus dipilih");
    isValid = false;
  } else {
    removeError("state");
  }

  // 6. VALIDASI KODE POS
  const zipCode = document.getElementById("zipCode").value.trim();
  if (zipCode === "") {
    showError("zipCode", "Kode Pos harus diisi");
    isValid = false;
  } else if (!/^\d+$/.test(zipCode)) {
    // Validasi bahwa kode pos hanya berisi angka
    showError("zipCode", "Kode Pos hanya boleh berisi angka");
    isValid = false;
  } else if (zipCode.length < 5) {
    // Validasi panjang kode pos
    showError("zipCode", "Kode Pos minimal 5 digit");
    isValid = false;
  } else {
    removeError("zipCode");
  }

  // 7. VALIDASI NOMOR HP
  const phone = document.getElementById("phone").value.trim();
  if (phone === "") {
    showError("phone", "Nomor HP harus diisi");
    isValid = false;
  } else if (!/^\d+$/.test(phone)) {
    // Validasi bahwa nomor HP hanya berisi angka
    showError("phone", "Nomor HP hanya boleh berisi angka");
    isValid = false;
  } else if (phone.length < 10 || phone.length > 13) {
    // Validasi panjang nomor HP
    showError("phone", "Nomor HP harus 10-13 digit");
    isValid = false;
  } else {
    removeError("phone");
  }

  // Kembalikan hasil validasi
  return isValid;
}

// Fungsi untuk menampilkan pesan error
function showError(fieldId, message) {
  // Dapatkan elemen input
  const field = document.getElementById(fieldId);

  // Tambahkan class Bootstrap untuk menandai error
  field.classList.add("is-invalid");

  // Cari div error atau buat baru jika belum ada
  let errorDiv = document.getElementById(fieldId + "Error");
  if (!errorDiv) {
    errorDiv = document.createElement("div");
    errorDiv.id = fieldId + "Error";
    errorDiv.className = "invalid-feedback"; // Class Bootstrap untuk error
    field.parentNode.appendChild(errorDiv);
  }

  // Isi pesan error
  errorDiv.textContent = message;
}

// Fungsi untuk menghapus pesan error
function removeError(fieldId) {
  // Dapatkan elemen input
  const field = document.getElementById(fieldId);

  // Hapus class error
  field.classList.remove("is-invalid");

  // Kosongkan pesan error jika ada
  const errorDiv = document.getElementById(fieldId + "Error");
  if (errorDiv) {
    errorDiv.textContent = "";
  }
}

// Fungsi untuk inisialisasi semua event listener
function initializeValidation() {
  // Event listener untuk validasi saat form disubmit
  const form = document.getElementById("shipping-form");
  if (form) {
    form.addEventListener("submit", function (event) {
      // Jika validasi gagal, cegah form disubmit
      if (!validateForm()) {
        event.preventDefault();
      }
    });
  }

  // Validasi real-time untuk input nomor HP
  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function () {
      const phone = this.value.trim();

      // Jika bukan angka, langsung beri pesan error
      if (phone !== "" && !/^\d+$/.test(phone)) {
        showError("phone", "Nomor HP hanya boleh berisi angka");
      } else {
        removeError("phone");
      }
    });
  }

  // Validasi real-time untuk input kode pos
  const zipCodeInput = document.getElementById("zipCode");
  if (zipCodeInput) {
    zipCodeInput.addEventListener("input", function () {
      const zipCode = this.value.trim();

      // Jika bukan angka, langsung beri pesan error
      if (zipCode !== "" && !/^\d+$/.test(zipCode)) {
        showError("zipCode", "Kode Pos hanya boleh berisi angka");
      } else {
        removeError("zipCode");
      }
    });
  }
}

// Jalankan fungsi inisialisasi setelah DOM selesai dimuat
document.addEventListener("DOMContentLoaded", initializeValidation);
