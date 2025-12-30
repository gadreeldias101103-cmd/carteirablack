const API = "https://SEU_BACKEND.ct.ws/api";

BlackCatPay.setPublicKey("SUA_PUBLIC_KEY");

async function depositar(){
  const token = localStorage.getItem("token");
  async function solicitarSaque(){
  const token = localStorage.getItem("token");

  const r = await fetch(API+"/saque_pix.php", {
    method: "POST",
    headers: {
      "Content-Type":"application/json",
      "Authorization":"Bearer "+token
    },
    body: JSON.stringify({
      valor: valor_saque.value,
      tipo: tipo_pix.value,
      chave: chave_pix.value
    })
  });

  const d = await r.json();
  msg.innerText = d.message;
}

  const card = {
    number: numero.value,
    holderName: nome.value,
    expirationMonth: mes.value,
    expirationYear: ano.value,
    cvv: cvv.value
  };

  const cardToken = await BlackCatPay.tokenize(card);

  const res = await fetch(`${API}/cartao_deposito.php`, {
    method:"POST",
    headers:{
      "Content-Type":"application/json",
      "Authorization":"Bearer "+token
    },
    body: JSON.stringify({
      valor: valor.value,
      card_token: cardToken.id
    })
  });

  const data = await res.json();
  alert(data.message);
}
