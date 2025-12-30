alert("auth.js carregou");

const API = "https://viraeraul.ct.ws/api";

function login() {
  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  fetch(`${API}/login.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, senha })
  })
  .then(r => r.json())
  .then(d => {
    if (d.token) {
      localStorage.setItem("token", d.token);
      location.href = "wallet.html";
    } else {
      alert(d.error || "Login inválido");
    }
  })
  .catch(() => alert("Erro de conexão"));
}

function register() {
  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  fetch(`${API}/register.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, senha })
  })
  .then(r => r.json())
  .then(d => {
    if (d.token) {
      localStorage.setItem("token", d.token);
      location.href = "wallet.html";
    } else {
      alert(d.error || "Erro ao cadastrar");
    }
  })
  .catch(() => alert("Erro de conexão"));
}

function logout() {
  localStorage.removeItem("token");
  location.href = "index.html";
}
