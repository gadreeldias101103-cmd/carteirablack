<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CARTEIRA BLACK - Depósito</title>
<style>
body { background:#111; color:#fff; font-family: Arial, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; flex-direction:column; }
h1 { color:#f5a623; }
.button { background:#f5a623; color:#111; padding:15px 30px; font-size:1.2rem; border-radius:8px; cursor:pointer; border:none; margin-bottom:20px; }
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); justify-content:center; align-items:center; }
.modal-content { background:#222; padding:30px; border-radius:10px; width:90%; max-width:400px; position:relative; text-align:center; }
.close { position:absolute; top:15px; right:20px; font-size:2rem; color:#fff; cursor:pointer; }
input { width:100%; padding:12px; margin:10px 0; border-radius:5px; border:none; font-size:1rem; }
.submit-btn { background:#f5a623; color:#111; border:none; padding:12px 25px; font-size:1rem; border-radius:8px; cursor:pointer; margin-top:10px; }
.submit-btn:hover { background:#ffce54; }
.success-msg { color:#4CAF50; display:none; margin-top:10px; }
.error-msg { color:#f44336; display:none; margin-top:10px; }
#threeDSFrame { width:100%; height:500px; border:none; display:none; margin-top:20px; }
</style>

<script src="https://api.blackcatpagamentos.com/v1/js"></script>

  // IMPORTANTE: Certifique-se de que o setPublicKey seja chamado assim que a tela for carregada.
await BlackCatPay.setPublicKey("pk_agQDa_tKZdjCoO9GPo9oJTb06lowViHauKtYAhMWAaTPufkE");
            
</head>
<body>

<h1>CARTEIRA BLACK</h1>
<button class="button" id="openModal">Depositar</button>

<div class="modal" id="depositModal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Depósito Seguro</h2>
        <form id="depositForm">
            <input type="text" name="card_number" placeholder="Número do Cartão" required>
            <input type="text" name="card_name" placeholder="Nome no Cartão" required>
            <input type="text" name="expiry" placeholder="MM/AA" required>
            <input type="text" name="cvv" placeholder="CVV" required>
            <input type="number" name="amount" placeholder="Valor do Depósito (R$)" required>
            <button type="submit" class="submit-btn">Depositar</button>
        </form>
        <div class="success-msg" id="successMsg">Depósito realizado com sucesso!</div>
        <div class="error-msg" id="errorMsg">Ocorreu um erro. Tente novamente.</div>
        <iframe id="threeDSFrame"></iframe>
    </div>
</div>

<script>
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');
const modal = document.getElementById('depositModal');
const threeDSFrame = document.getElementById('threeDSFrame');

openModal.onclick = () => modal.style.display = 'flex';
closeModal.onclick = () => modal.style.display = 'none';
window.onclick = e => { if(e.target == modal) modal.style.display = 'none'; }

document.addEventListener("DOMContentLoaded", async () => {
    await BlackCatPay.setPublicKey("pk_agQDa_tKZdjCoO9GPo9oJTb06lowViHauKtYAhMWAaTPufkE");
});

const depositForm = document.getElementById('depositForm');
const successMsg = document.getElementById('successMsg');
const errorMsg = document.getElementById('errorMsg');

depositForm.addEventListener('submit', async function(e){
    e.preventDefault();
    successMsg.style.display = 'none';
    errorMsg.style.display = 'none';
    threeDSFrame.style.display = 'none';

    const card = {
        number: depositForm.card_number.value,
        holderName: depositForm.card_name.value,
        expMonth: depositForm.expiry.value.split('/')[0],
        expYear: depositForm.expiry.value.split('/')[1],
        cvv: depositForm.cvv.value
    };
    const amount = depositForm.amount.value;

    try {
        const token = await BlackCatPay.encrypt(card);

        const response = await fetch("process_payment.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ token: token, amount: amount })
        });
try {
  const card = {
    number: "123456789",
    holderName: "João Silva",
    expMonth: 1,
    expYear: 2026,
    cvv: "456"
  }
    
  const token = await BlackCatPay.encrypt(card);
  console.error('Token:', token);
} catch (error) {
  console.error('Erro na requisição:', error);
}
        const result = await response.json();

        if(result.success){
            if(result.data && result.data.three_ds_url){
                threeDSFrame.src = result.data.three_ds_url;
                threeDSFrame.style.display = 'block';
            } else {
                successMsg.style.display = 'block';
                depositForm.reset();
            }
        } else {
            errorMsg.style.display = 'block';
            console.error(result.error);
        }

    } catch(err){
        console.error(err);
        errorMsg.style.display = 'block';
    }
});
</script>
</body>
</html>