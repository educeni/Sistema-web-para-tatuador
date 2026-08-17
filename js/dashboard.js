const meses = [
    "Janeiro",
    "Fevereiro",
    "Março",
    "Abril",
    "Maio",
    "Junho",
    "Julho",
    "Agosto",
    "Setembro",
    "Outubro",
    "Novembro",
    "Dezembro"
];

const hoje = new Date();

const diaAtual = hoje.getDate();
const mesAtual = hoje.getMonth();
const anoAtual = hoje.getFullYear();

document.getElementById("mes").textContent =
`${meses[mesAtual]} ${anoAtual}`;

const calendario = document.getElementById("calendario");

const primeiroDia = new Date(anoAtual, mesAtual, 1).getDay();

const ultimoDia = new Date(anoAtual, mesAtual + 1, 0).getDate();

/* espaços vazios antes do dia 1 */

for(let i = 0; i < primeiroDia; i++){

    const vazio = document.createElement("div");

    calendario.appendChild(vazio);

}

/* dias do mês */

for(let dia = 1; dia <= ultimoDia; dia++){

    const quadrado = document.createElement("div");

    quadrado.classList.add("dia");

    quadrado.textContent = dia;

    if(dia === diaAtual){

        quadrado.classList.add("hoje");

    }

    calendario.appendChild(quadrado);

}