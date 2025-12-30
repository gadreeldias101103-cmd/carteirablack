const API = "https://SEU_BACKEND.ct.ws/api";

function login() {
  fetch(`${API}/login.php`, {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({
      email: email.value,
      senha: senha.value
    })
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.token){
      localStorage.setItem("token", d.token);
      window.location = "wallet.html";
    } else alert("Login inválido");
  });
}

function register() {
  fetch(`${API}/register.php`, {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({
      email: email.value,
      senha: senha.value
    })
  }).then(()=>location.href="index.html");
}

function logout(){
  localStorage.removeItem("token");
  location.href="index.html";
}
