//Menu lateral
var closeButton = document.querySelector('.close-menu');

closeButton.addEventListener('click', function() {
  // Verificar se o botão possui a classe 'open-menu'
  if (closeButton.classList.contains('open-menu')) {    
    // Remover as classes e estilos para voltar ao estado inicial
    closeButton.classList.remove('open-menu', 'bx-flip-horizontal');
    closeButton.classList.add('close-menu');
    closeButton.style.transition = 'left 0.5s';
    document.querySelector('.menu').style.width = '15vw';
    document.querySelector('.menu').style.transition = 'width 0.5s';
    document.querySelector('.periodic-table').style.left = '6vw';
    document.querySelector('.periodic-table').style.transition = 'left 0.5s';
    document.querySelector('.periodic-table').style.transition = 'transform 0.7s';
    document.querySelector('.periodic-table').style.transform = 'scale(0.8)'; 
    document.querySelector('.mascot-inspira').style.width = '5vw';
    //Verifica se a modal do audio está aberto  
    modalOpen = false;  
    
  } else {    
    // Adicionar as classes e estilos para aplicar as alterações
    closeButton.classList.remove('close-menu');
    closeButton.classList.add('open-menu', 'bx-flip-horizontal');
    closeButton.style.transition = 'left 0.5s';
    document.querySelector('.menu').style.width = '0';
    document.querySelector('.menu').style.transition = 'width 0.5s';
    document.querySelector('.periodic-table').style.left = '0';
    document.querySelector('.periodic-table').style.transition = 'left 0.5s';
    document.querySelector('.periodic-table').style.transition = 'transform 0.7s';
    document.querySelector('.periodic-table').style.transform = 'scale(0.9)'; 
    document.querySelector('.mascot-inspira').style.width = '6vw';
    //Se tiver aberto fecha a modal de audio
    if(modalOpen === true ){
      hideModalAudio();
      audioPlayerInitialized = false;
    }    
  }
});

// Função para buscar e exibir as informações de um elemento
function showElementInfo(id) {
  // Carrega o arquivo JSON
  fetch("./json/elements.json")
    .then(response => response.json())
    .then(elements => {
      // Busca o elemento pelo id
      const element = elements.find(e => e.id === id); 
      // Cria as tags <p> com os dados do elemento
      const atomicNumber = document.createElement("p");
      atomicNumber.setAttribute("title", "Número Atômico");
      atomicNumber.setAttribute("class", "atomic");
      atomicNumber.setAttribute("alt", "Número Atômico");
      atomicNumber.textContent = element.number;

      const symbol = document.createElement("p");
      symbol.setAttribute("title", "Símbolo");
      symbol.setAttribute("class", "symbol");
      symbol.setAttribute("alt", "element.symbol");
      symbol.textContent = element.symbol;

      const atomName = document.createElement("p");
      atomName.setAttribute("title", "Nome");
      atomName.setAttribute("class", "atom-name");
      atomName.setAttribute("alt", element.name);
      atomName.textContent = element.name;

      const weight = document.createElement("p");
      weight.setAttribute("title", "Peso");
      weight.setAttribute("class", "weight");
      weight.setAttribute("alt", element.weight);
      weight.textContent = element.weight;

      // Adiciona as tags <p> à <li> com o id correspondente
      const li = document.getElementById(id);
      li.classList.add(element.class);
      li.appendChild(atomicNumber);
      li.appendChild(symbol);
      li.appendChild(atomName);
      li.appendChild(weight);
    });
}

// Chama a função para exibir as informações dos elementos
showElementInfo("hydrogen");
showElementInfo("helium");
showElementInfo("lithium");
showElementInfo("beryllium");
showElementInfo("boron");
showElementInfo("carbon");
showElementInfo("nitrogen");
showElementInfo("oxygen");
showElementInfo("fluorine");
showElementInfo("neon");
showElementInfo("sodium");
showElementInfo("magnesium");
showElementInfo("aluminum");
showElementInfo("silicon");
showElementInfo("phosphorus");
showElementInfo("sulfur");
showElementInfo("chlorine");
showElementInfo("argon");
showElementInfo("potassium");
showElementInfo("calcium");
showElementInfo("scandium");
showElementInfo("titanium");
showElementInfo("vanadium");
showElementInfo("chromium");
showElementInfo("manganese");
showElementInfo("iron");
showElementInfo("cobalt");
showElementInfo("nickel");
showElementInfo("copper");
showElementInfo("zinc");
showElementInfo("gallium");
showElementInfo("germanium");
showElementInfo("arsenic");
showElementInfo("selenium");
showElementInfo("bromine");
showElementInfo("krypton");
showElementInfo("rubidium");
showElementInfo("strontium");
showElementInfo("yttrium");
showElementInfo("zirconium");
showElementInfo("niobium");
showElementInfo("molybdenum");
showElementInfo("technetium");
showElementInfo("ruthenium");
showElementInfo("rhodium");
showElementInfo("palladium");
showElementInfo("silver");
showElementInfo("cadmium");
showElementInfo("indium");
showElementInfo("tin");
showElementInfo("antimony");
showElementInfo("tellurium");
showElementInfo("iodine");
showElementInfo("xenon");
showElementInfo("cesium");
showElementInfo("barium");
showElementInfo("lanthanum");
showElementInfo("cerium");
showElementInfo("praseodymium");
showElementInfo("neodymium");
showElementInfo("promethium");
showElementInfo("samarium");
showElementInfo("europium");
showElementInfo("gadolinium");
showElementInfo("terbium");
showElementInfo("dysprosium");
showElementInfo("holmium");
showElementInfo("erbium");
showElementInfo("thulium");
showElementInfo("ytterbium");
showElementInfo("lutetium");
showElementInfo("hafnium");
showElementInfo("tantalum");
showElementInfo("tungsten");
showElementInfo("rhenium");
showElementInfo("osmium");
showElementInfo("iridium");
showElementInfo("platinum");
showElementInfo("gold");
showElementInfo("mercury");
showElementInfo("thallium");
showElementInfo("lead");
showElementInfo("bismuth");
showElementInfo("polonium");
showElementInfo("astatine");
showElementInfo("radon");
showElementInfo("francium");
showElementInfo("radium");
showElementInfo("actinium");
showElementInfo("thorium");
showElementInfo("protactinium");
showElementInfo("uranium");
showElementInfo("neptunium");
showElementInfo("plutonium");
showElementInfo("americium");
showElementInfo("curium");
showElementInfo("berkelium");
showElementInfo("californium");
showElementInfo("einsteinium");
showElementInfo("fermium");
showElementInfo("mendelevium");
showElementInfo("nobelium");
showElementInfo("lawrencium");
showElementInfo("rutherfordium");
showElementInfo("dubnium");
showElementInfo("seaborgium");
showElementInfo("bohrium");
showElementInfo("hassium");
showElementInfo("meitnerium");
showElementInfo("darmstadtium");
showElementInfo("roentgenium");
showElementInfo("copernicium");
showElementInfo("nihonium");
showElementInfo("flerovium");
showElementInfo("moscovium");
showElementInfo("livermorium");
showElementInfo("tennessine");
showElementInfo("oganesson");

//Seleciona Grupo/Periodo
const group1  = document.querySelector("#group-1");
const group2  = document.querySelector("#group-2");
const group3  = document.querySelector("#group-3");
const group4  = document.querySelector("#group-4");
const group5  = document.querySelector("#group-5");
const group6  = document.querySelector("#group-6");
const group7  = document.querySelector("#group-7");
const group8  = document.querySelector("#group-8");
const group9  = document.querySelector("#group-9");
const group10 = document.querySelector("#group-10");
const group11 = document.querySelector("#group-11");
const group12 = document.querySelector("#group-12");
const group13 = document.querySelector("#group-13");
const group14 = document.querySelector("#group-14");
const group15 = document.querySelector("#group-15");
const group16 = document.querySelector("#group-16");
const group17 = document.querySelector("#group-17");
const group18 = document.querySelector("#group-18");

const period1 = document.querySelector("#period-1");
const period2 = document.querySelector("#period-2");
const period3 = document.querySelector("#period-3");
const period4 = document.querySelector("#period-4");
const period5 = document.querySelector("#period-5");
const period6 = document.querySelector("#period-6");
const period7 = document.querySelector("#period-7");

const lanthanide = document.querySelector("#lanthanide-start");
const actinide   = document.querySelector("#actinide-start");



let currentGroupPeriod = null;

function handleClick(group_period) {
  const allLis = document.querySelectorAll("li");

  // Verifica se está clicando novamente no mesmo ID
  if (group_period === currentGroupPeriod) {
    // Remove o estilo e a classe "no-hover" de todos os elementos
    allLis.forEach((li) => {
      li.removeAttribute("style");
      li.classList.remove("no-hover");
    });

    currentGroupPeriod = null; // Define o ID atual como null, indicando que nenhum grupo ou período está selecionado
  } else {
    // Remove o estilo e a classe "no-hover" de todos os elementos
    allLis.forEach((li) => {
      li.removeAttribute("style");
      li.classList.remove("no-hover");
    });

    // Adiciona a classe "no-hover" aos elementos que não correspondem ao grupo ou período selecionado
    allLis.forEach((li) => {
      if (!li.classList.contains(group_period) && !li.classList.contains("groups-periods") && !li.classList.contains("no-element")) {
        li.style.backgroundColor = "white";
        li.classList.add("no-hover");
      }
    });

    // Remove a classe "no-hover" dos elementos correspondentes ao grupo ou período selecionado
    allLis.forEach((li) => {
      if (li.classList.contains(group_period)) {
        li.classList.remove("no-hover");
      }
    });

    currentGroupPeriod = group_period; // Define o novo ID como o ID atual
  }
}

// Função para manipular o evento de pressionar a tecla ESC
function handleKeyPress(event) {
  if (event.key === "Escape") {
    handleClickMenu(currentGroupPeriod);
  }
}

// Adiciona o ouvinte de evento para o pressionar da tecla ESC
document.addEventListener("keydown", handleKeyPress);

group1.addEventListener("click", function() {
  handleClick("group-1");
  handleHydrogen();
});

function handleHydrogen() {
  const hydrogen = document.querySelector(".hydrogen");
  hydrogen.removeAttribute("style");
  hydrogen.classList.remove("no-hover");
}

group2.addEventListener("click", function() {
  handleClick("group-2");
});

group3.addEventListener("click", function() {
  handleClick("group-3");
});

group4.addEventListener("click", function() {
  handleClick("group-4");
});

group5.addEventListener("click", function() {
  handleClick("group-5");
});

group6.addEventListener("click", function() {
  handleClick("group-6");
});

group7.addEventListener("click", function() {
  handleClick("group-7");
});

group8.addEventListener("click", function() {
  handleClick("group-8");
});

group9.addEventListener("click", function() {
  handleClick("group-9");
});

group10.addEventListener("click", function() {
  handleClick("group-10");
});

group11.addEventListener("click", function() {
  handleClick("group-11");
});

group12.addEventListener("click", function() {
  handleClick("group-12");
});

group13.addEventListener("click", function() {
  handleClick("group-13");
});

group14.addEventListener("click", function() {
  handleClick("group-14");
});

group15.addEventListener("click", function() {
  handleClick("group-15");
});

group16.addEventListener("click", function() {
  handleClick("group-16");
});
group17.addEventListener("click", function() {
  handleClick("group-17");
});

group18.addEventListener("click", function() {
  handleClick("group-18");
});

period1.addEventListener("click", function() {
  handleClick("period-1");
});

period2.addEventListener("click", function() {
  handleClick("period-2");
});

period3.addEventListener("click", function() {
  handleClick("period-3");
});

period4.addEventListener("click", function() {
  handleClick("period-4");
});

period5.addEventListener("click", function() {
  handleClick("period-5");
});

period6.addEventListener("click", function() {
  handleClick("period-6");
  handleLanthanide();
});

period7.addEventListener("click", function() {
  handleClick("period-7");
  handleActinide();
});

function handleLanthanide() {  
  const lanthanideElements = document.querySelectorAll(".lanthanide");
  lanthanideElements.forEach(element => {    
    element.removeAttribute("style");
    element.classList.remove("no-hover");
  });
}

function handleActinide() {  
  const lanthanideElements = document.querySelectorAll(".actinide");
  lanthanideElements.forEach(element => {    
    element.removeAttribute("style");
    element.classList.remove("no-hover");
  });
}

lanthanide.addEventListener("click", function() {
  handleClick("lanthanide");
});

actinide.addEventListener("click", function() {
  handleClick("actinide");
});

//Menu lateral
const alkali_menu = document.querySelector("#alkali-menu");
const hydrogen_menu = document.querySelector("#hydrogen-menu");
const alkaline_menu = document.querySelector("#alkaline-menu");
const transition_metal_menu = document.querySelector("#transition-metal-menu");
const boron_menu = document.querySelector("#boron-menu");
const carbon_menu = document.querySelector("#carbon-menu");
const nitrogen_menu = document.querySelector("#nitrogen-menu");
const calogens_menu = document.querySelector("#calogens-menu");
const halogen_menu = document.querySelector("#halogen-menu");
const noble_gases_menu = document.querySelector("#noble-gases-menu");

const lanthanide_menu = document.querySelector("#lanthanide-menu");
const actinide_menu = document.querySelector("#actinide-menu");

const metals_meu = document.querySelector("#metals-menu");
const nonmetals_menu = document.querySelector("#nonmetals-menu");

let currentGroupPeriodMenu = null;

function handleClickMenu(group_period_menu) {
  const allLis = document.querySelectorAll("li");

  // Verifica se está clicando novamente no mesmo ID
  if (group_period_menu === currentGroupPeriodMenu) {
    // Remove o estilo e a classe "no-hover" de todos os elementos
    allLis.forEach((li) => {
      li.removeAttribute("style");
      li.classList.remove("no-hover");
    });

    currentGroupPeriodMenu = null; // Define o ID atual como null, indicando que nenhum grupo ou período está selecionado
    // Restaura a cor original do botão
    alkali_menu.style.color = "";
    hydrogen_menu.style.color = "";
    alkaline_menu.style.color = "";
    transition_metal_menu.style.color = "";
    boron_menu.style.color = "";
    carbon_menu.style.color = "";
    nitrogen_menu.style.color = "";
    calogens_menu.style.color = "";
    halogen_menu.style.color = "";
    noble_gases_menu.style.color = "";
    lanthanide_menu.style.color = ""; 
    actinide_menu.style.color = ""; 
    metals_meu.style.color = "";
    nonmetals_menu.style.color = "";    
  } else {
    // Remove o estilo e a classe "no-hover" de todos os elementos
    allLis.forEach((li) => {
      li.removeAttribute("style");
      li.classList.remove("no-hover");
    });

    // Adiciona a classe "no-hover" aos elementos que não correspondem ao grupo ou período selecionado
    allLis.forEach((li) => {
      if (!li.classList.contains(group_period_menu) && !li.classList.contains("groups-periods") && !li.classList.contains("no-element")) {
        li.style.backgroundColor = "white";
        li.classList.add("no-hover");
      }
    });

    // Remove a classe "no-hover" dos elementos correspondentes ao grupo ou período selecionado
    allLis.forEach((li) => {
      if (li.classList.contains(group_period_menu)) {
        li.classList.remove("no-hover");
      }
    });

    currentGroupPeriodMenu = group_period_menu; // Define o novo ID como o ID atual

    // Altera a cor da fonte do botão correspondente ao grupo ou período selecionado
    if(group_period_menu === "group-1"){
      alkali_menu.style.color = "rgb(197, 134, 45)";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";  
    }else if(group_period_menu === "hydrogen"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "rgb(160,200,209)";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = "";
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";    
    }else if(group_period_menu === "group-2"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "rgb(201, 168, 97)";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";      
    }else if(group_period_menu === "group-transition-metal"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "rgb(214, 179, 206)";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";        
    }else if(group_period_menu === "group-13"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "rgb(20, 145, 128)";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";      
    }else if(group_period_menu === "group-14"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "rgb(33, 34, 30)";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";        
    }else if(group_period_menu === "group-15"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "rgb(67, 119, 128)";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";    
    }else if(group_period_menu === "group-16"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "rgb(57, 137, 168)";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";      
    }else if(group_period_menu === "group-17"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "rgb(204, 179, 82)";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";       
    }else if(group_period_menu === "group-18"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "rgb(107, 79, 133)";
      lanthanide_menu.style.color = "";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";     
    }else if (group_period_menu === "lanthanide") {
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      lanthanide_menu.style.color = "rgb(161, 132, 97)";
      actinide_menu.style.color = ""; 
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";     
    } else if (group_period_menu === "actinide") {
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      actinide_menu.style.color = "rgb(206, 123, 159)";
      lanthanide_menu.style.color = "";
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "";     
    } else if (group_period_menu === "group-metals"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      actinide_menu.style.color = "";
      lanthanide_menu.style.color = "";
      metals_meu.style.color = "rgb(14, 7, 96)";
      nonmetals_menu.style.color = "";      
    } else if (group_period_menu === "group-nonmetals"){
      alkali_menu.style.color = "";
      hydrogen_menu.style.color = "";
      alkaline_menu.style.color = "";
      transition_metal_menu.style.color = "";
      boron_menu.style.color = "";
      carbon_menu.style.color = "";
      nitrogen_menu.style.color = "";
      calogens_menu.style.color = "";
      halogen_menu.style.color = "";
      noble_gases_menu.style.color = "";
      actinide_menu.style.color = "";
      lanthanide_menu.style.color = "";
      metals_meu.style.color = "";
      nonmetals_menu.style.color = "rgb(47, 179, 166)";    
    }
  }
}
closeButton.addEventListener('click', function(){
    handleClickMenu(currentGroupPeriodMenu);
});
// Função para manipular o evento de pressionar a tecla ESC
function handleKeyPress(event) {
  if (event.key === "Escape") {
    handleClickMenu(currentGroupPeriodMenu);
  }
}

// Adiciona o ouvinte de evento para o pressionar da tecla ESC
document.addEventListener("keydown", handleKeyPress);

alkali_menu.addEventListener("click", function() {
  handleClickMenu("group-1");
});

hydrogen_menu.addEventListener("click", function() {
  handleClickMenu("hydrogen");
});

alkaline_menu.addEventListener("click", function() {
  handleClickMenu("group-2");
});

transition_metal_menu.addEventListener("click", function() {
  handleClickMenu("group-transition-metal");
});

boron_menu.addEventListener("click", function() {
  handleClickMenu("group-13");
});

carbon_menu.addEventListener("click", function() {
  handleClickMenu("group-14");
});

nitrogen_menu.addEventListener("click", function() {
  handleClickMenu("group-15");
});

calogens_menu.addEventListener("click", function() {
  handleClickMenu("group-16");
});

halogen_menu.addEventListener("click", function() {
  handleClickMenu("group-17");
});

noble_gases_menu.addEventListener("click", function() {
  handleClickMenu("group-18");
});

lanthanide_menu.addEventListener("click", function() {
  handleClickMenu("lanthanide");
});

actinide_menu.addEventListener("click", function() {
  handleClickMenu("actinide");
});

metals_meu.addEventListener("click", function(){
  handleClickMenu("group-metals");
});

nonmetals_menu.addEventListener("click", function(){
  handleClickMenu("group-nonmetals");
});

//Modal
const modalElement = document.querySelector('.modal-element');
const contentElement = document.querySelector('.content-element');
const openModalButtons = document.querySelectorAll('.element');
var closeModalElements = document.getElementsByClassName('close-modal-element');

for (var i = 0; i < closeModalElements.length; i++) {
  var closeModalElement = closeModalElements[i];
  
  closeModalElement.addEventListener('click', function() {    
    modalElement.style.display = 'none';
  });
}

openModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const elementId = button.id; // obtém o id do botão clicado
    fetch('./json/elements.json')
      .then(response => response.json())
      .then(elements => {
        const element = elements.find(e => e.id === elementId); // busca as informações do elemento no arquivo JSON
        if (element) {
          // monta o HTML para exibir as informações do elemento
          const electrons = element.electrosphere.split('-').map(n => parseInt(n)); // separa os números da camada de valência
          const electronsList = electrons.map((n, index) => {
            if (index === electrons.length - 1) {
              return `<li class="electrons-li">${n}</li>`;
            } else {
              return `<li class="electrons-li">${n} <i class='bx bx-minus'></i></li>`;
            }
          }).join('');
          const elementClass = element.class === "nonmetal" ? "Ametal" :
                               element.class === "noble-gas" ? "Gás nobre" :
                               element.class === "alkali" ? "Metal alcalino" :
                               element.class === "alkaline" ? "Metal alcalinoterroso" :
                               element.class === "metalloid" ? "Metal/Ametal" :
                               element.class === "post-transition-metals" ? "Metal" :
                               element.class === "transition-metal" ? "Metal transição" :
                               element.class === "halogen" ? "Halogênio" :
                               element.class === "lanthanide" ? "Lantanídeo" :
                               element.class === "actinide" ? "Actinídeo" :
                               element.class === "unknown" ? "Desconhecido" :
                               element.class;
          const html = `
          <div class="element-wrapper">            
            <h1 class="element-title" >${element.name}</h1>
            <div class="electrosphere-div">
              <ul class="electrosphere-ul">${electronsList}</ul>
            </div>
          </div>
            <div class="atom">
              <div class="proton"></div>              
            </div>
            
            <div class="element-text">
            </div>
            <ul class="atom-info">
              <li><p><i class='bx bx-minus'></i> <span>Classificação:</span> ${elementClass}</li>
              <li><p><i class='bx bx-minus'></i> <span>Número atômico:</span> ${element.number}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Símbolo:</span> ${element.symbol}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Peso atômico:</span> ${element.weight}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Localização:</span> ${element.location}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Configuração Eletrônica:</span> ${element.configuration}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Potencial de Ionização:</span> ${element.ionization}</p></li>
              <li><p><i class='bx bx-minus'></i> <span>Ponto de Ebulição:</span> ${element.boiling}<p></li>
              <li><p><i class='bx bx-minus'></i> <span>Ponto de Fusão:</span> ${element.fusion}<p></li>
              <li><p><i class='bx bx-minus'></i> <span>Isótopos:</span> ${element.isotopes}<p></li>
              <li><p><i class='bx bx-minus'></i> <span>Descoberta:</span> ${element.uncovered}<p></li>
            </ul>
         
          `;         
          contentElement.innerHTML = html;
          const elementText = document.querySelector('.element-text');
          elementText.style.paddingRight = '20px';
          fetch(`./elementos/${elementId}.html`) // busca o conteúdo HTML correspondente ao ID do elemento
            .then(response => response.text())
            .then(elementHtml => {
              const elementText = document.querySelector('.element-text');
              elementText.innerHTML = elementHtml; // insere o conteúdo HTML dentro da div "element-text"
            })
            .catch(error => console.error(error));

          modalElement.style.display = 'flex';
          //Proton
          const size = 1;
          const protonElement = document.querySelector('.proton');
          protonElement.style.width = size + 'vw';
          protonElement.style.height = size + 'vw';
          const atom = document.querySelector('.atom'); // seleciona o elemento que irá conter os círculos
          //Eletrosfera
          let lastElectrosphere; // variável para armazenar a referência da última eletrosfera criada
          for (let i = 0; i < electrons.length; i++) {
            const rotationDuration = 10 - i; // define a duração da animação de rotação de acordo com o tamanho da eletrosfera
            const sizeElecrosphere = size + 2 + i * 2;
            const electrosphere = document.createElement('div'); // cria um novo elemento <div> para o círculo
            electrosphere.classList.add('electrosphere'); // adiciona a classe 'circle' ao elemento <div>
            electrosphere.style.width = `${sizeElecrosphere}vw`; // define a largura do círculo
            electrosphere.style.height = `${sizeElecrosphere}vw`; // define a altura do círculo
            electrosphere.style.transform = `translate(-50%, -${size / 2.5}vh)`; // Move para cima e centraliza verticalmente
            electrosphere.style.animation = `rotate ${rotationDuration}s linear infinite`; // adiciona a animação de rotação à eletrosfera
            atom.appendChild(electrosphere); // adiciona o círculo ao elemento que irá contê-lo
            //Elétron
            const electronsInCurrentElectrosphere = electrons[i];
            const radius = sizeElecrosphere / 2;
            for (let j = 0; j < electronsInCurrentElectrosphere; j++) {
              const electron = document.createElement('div');
              electron.classList.add('electron');
              electron.style.position = 'absolute';
              const angle = (2 * Math.PI / electronsInCurrentElectrosphere) * j;
              const x = radius * Math.cos(angle);
              const y = radius * Math.sin(angle);
              electron.style.left = `calc(50% + ${x}vw - 0.25vw)`;
              electron.style.top = `calc(50% - ${y}vw - 0.25vw)`;
              electrosphere.appendChild(electron);
              //Armazena a referência da última eletrosfera criada
              lastElectrosphere = electrosphere;
            }
          } 
          const lastElectrosphereSize = parseInt(lastElectrosphere.style.width) + 1; // Obtém o tamanho da última eletrosfera e adiciona mais 1
          const atomDiv = document.querySelector('.atom');
          atomDiv.style.height = `${lastElectrosphereSize}vw`; // Define a altura do elemento .atom em vw          
         
        }
      })
      .catch(error => console.error(error));
  });
});

document.addEventListener('click', (event) => {
  if (event.target.classList.contains('modal-element')) {
    modalElement.style.display = 'none';
  }
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    modalElement.style.display = 'none';
  }
});


//Global
var currentVideoUrl = '';
var modalOpen = false;
var player;
//Reseta posição inicial da tabela
function resetTable(){
  periodicTable.style.transform = 'scale(0.8)';
  periodicTable.style.left = '6vw';
  periodicTable.style.transition = 'transform 0.7s';
}

// Resta o menu e elementos
function clearSelection(){
  alkali_menu.style.color = "";
  hydrogen_menu.style.color = "";
  alkaline_menu.style.color = "";
  transition_metal_menu.style.color = "";
  boron_menu.style.color = "";
  carbon_menu.style.color = "";
  nitrogen_menu.style.color = "";
  calogens_menu.style.color = "";
  halogen_menu.style.color = "";
  noble_gases_menu.style.color = "";
  actinide_menu.style.color = "";
  lanthanide_menu.style.color = "";
  metals_meu.style.color = "";
  nonmetals_menu.style.color = "";  
  const allLis = document.querySelectorAll("li"); 
  allLis.forEach((li) => {
    li.removeAttribute("style");
    li.classList.remove("no-hover");
  });
}

//Fechar modal com ESC
function closeModalOnEsc(event) {
  if (event.keyCode === 27) {
    hideModalAudio();
    hideModalVideo();
    resetTable();  
    modalOpen = false; 
    if(closeButton.classList.contains('open-menu')){
      closeButton.click();
    }      
  }
}

document.addEventListener('keydown', closeModalOnEsc);

//Modal Audio
var modalAudio = document.querySelector('.modal-audio');
var closeModalAudio = document.querySelector('.close-modal-audio');
var periodicTable = document.querySelector('.periodic-table');
var groupsPeriods = document.getElementsByClassName('groups-periods');
var mascotInspira = document.querySelector('.mascot-inspira')
var audioPlayerInitialized = false;
var currentPlayer = null;

function createPlayerAudio(videoUrl) {
  if (currentPlayer) {
    currentPlayer.unload().then(function() {
      currentPlayer.destroy().then(function() {
        currentPlayer = null;
        initializePlayerAudio(videoUrl);
      });
    });
  } else {
    initializePlayerAudio(videoUrl);
  }
}

function initializePlayerAudio(videoUrl) {
  var loadingIconAudio = document.getElementById('loading-icon-audio');
  loadingIconAudio.style.display = 'block';

  currentPlayer = new Vimeo.Player('player-audio', {
    url: videoUrl,
    autoplay: false,
    controls: true,
    responsive: true,
    pip: false
  });

  currentPlayer.on('loaded', function() {
    loadingIconAudio.style.display = 'none';
  });

  audioPlayerInitialized = true;
}

function hideModalAudio() {
  modalAudio.style.display = 'none';  
  for (var i = 0; i < groupsPeriods.length; i++) {
    var groupPeriod = groupsPeriods[i];
    groupPeriod.style.pointerEvents = '';
  }
  
  if (player) {
    player.unload().then(function() {
      player.destroy();
      player = null;
    });
  }

  if (player && audioPlayerInitialized) {
    player.unload().then(function() {
      player.destroy();
      player = null;
      audioPlayerInitialized = false; 
      currentVideoUrl = '';
    });
  }
  mascotInspira.style.width = '5vw'; 
}

function showModalAudio() {
  modalAudio.style.display = 'flex';

  for (var i = 0; i < groupsPeriods.length; i++) {
    var groupPeriod = groupsPeriods[i];
    groupPeriod.style.pointerEvents = 'none';
  }  
  periodicTable.style.transform = 'scale(0.5)';
  periodicTable.style.left = '27vw';
  periodicTable.style.transition = 'transform 0.7s';
  mascotInspira.style.width = '4vw'; 
}

var hydrogenMenu        = document.getElementById('hydrogen-menu');
var alkaliMenu          = document.getElementById('alkali-menu');
var alkalineMenu        = document.getElementById('alkaline-menu');
var transitionMetalMenu = document.getElementById('transition-metal-menu');
var boronMenu           = document.getElementById('boron-menu');
var carbonMenu          = document.getElementById('carbon-menu');
var nitrogenMenu        = document.getElementById('nitrogen-menu');
var calogensMenu        = document.getElementById('calogens-menu');
var halogenMenu         = document.getElementById('halogen-menu');
var nobleGasesMenu      = document.getElementById('noble-gases-menu');
var lanthanideMenu      = document.getElementById('lanthanide-menu');
var actinideMenu        = document.getElementById('actinide-menu');

var metalsMenu = document.getElementById('metals-menu');
var nometalsMenu = document.getElementById('nonmetals-menu');

closeModalAudio.addEventListener('click', function(){  
    hideModalAudio();
    resetTable();    
    clearSelection();    
    modalOpen = false; 
});

hydrogenMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/835549123/882456def2';
  if (modalOpen && event.target === hydrogenMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

alkaliMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/833444781/7d3e52c703';
  if (modalOpen && event.target === alkaliMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

alkalineMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/833736511/54aba38cb4';
  if (modalOpen && event.target === alkalineMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

transitionMetalMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/834169166/5e75bf0387';
  if (modalOpen && event.target === transitionMetalMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

boronMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/833780230/992009ff54';
  if (modalOpen && event.target === boronMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

carbonMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/834012754/baf3f94cc2';
  if (modalOpen && event.target === carbonMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

nitrogenMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/834041924/ef205c38b1';
  if (modalOpen && event.target === nitrogenMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

calogensMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/834059544/40c97116da';
  if (modalOpen && event.target === calogensMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

halogenMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/836299608/252a78f658';
  if (modalOpen && event.target === halogenMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

nobleGasesMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/835808032/7d0d54ccb5';
  if (modalOpen && event.target === nobleGasesMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

lanthanideMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/835455363/eee68ab638';
  if (modalOpen && event.target === lanthanideMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

actinideMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/835898136/cf9ded6d11';
  if (modalOpen && event.target === actinideMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

metalsMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/835827198/fa935cf686';
  if (modalOpen && event.target === metalsMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});
nometalsMenu.addEventListener('click', function(event) {
  var videoUrl = 'https://vimeo.com/836145755/74282cecb9';
  if (modalOpen && event.target === nometalsMenu && currentVideoUrl === videoUrl) {
    hideModalAudio();
    resetTable(); 
    modalOpen = false;
  } else {
    createPlayerAudio(videoUrl);
    showModalAudio();
    modalOpen = true;
    currentVideoUrl = videoUrl;
  }
});

//Modal Video
var modalVideo = document.querySelector('.modal-video');
var closeModalVideo = document.querySelector('.close-modal-video');
var videoPlayerInitialized = false;

function createPlayerVideo(videoUrl){
  if(player && typeof player !== 'undefined' && videoPlayerInitialized){
    player.unload().then(function(){
      player.destroy().then(function(){
        player = null;
        initializePlayerVideo(videoUrl);
      });
    });
  } else  {
    initializePlayerVideo(videoUrl);
  }
}

function initializePlayerVideo(videoUrl){
  var loadingIconVideo = document.getElementById('loading-icon-video');
  loadingIconVideo.style.display = 'block';

  player = new Vimeo.Player('player-video',{
    url: videoUrl,
    autoplay: false,
    controls: true,
    responsive: true,
    pip: false
  });

  player.on('loaded', function(){
    loadingIconVideo.style.display= 'none';
  });

  videoPlayerInitialized = true;
}

function hideModalVideo(){
  modalVideo.style.display = 'none';
  if(player){
    player.unload().then(function(){
      player.destroy();
      player = null;
    });
  }

  if (player && videoPlayerInitialized) { 
    player.unload().then(function() {
      player.destroy();
      player = null;
      videoPlayerInitialized = false;
      currentVideoUrl = '';
    });
  }
}

function showModalVideo() {
  modalVideo.style.display = 'flex';
}

var originElementsMenu      = document.getElementById('origin-elements-menu'); 
var tecnologiElementsMenu   = document.getElementById('tecnologi-elements-menu');
var tableWhatForMenu        = document.getElementById('table-what-for-menu');

closeModalVideo.addEventListener('click', function(){
  hideModalVideo();
  modalOpen = false;
});

originElementsMenu.addEventListener('click', function(){
  var videoUrl = 'https://vimeo.com/823028907';
  clearSelection();
  hideModalAudio();
  resetTable();
  showModalVideo();
  createPlayerVideo(videoUrl);  
  modalOpen = true;
  currentVideoUrl = videoUrl;
});

tecnologiElementsMenu.addEventListener('click', function(){
  var videoUrl = 'https://vimeo.com/822966222';
  clearSelection();
  hideModalAudio();
  resetTable();
  createPlayerVideo(videoUrl);
  showModalVideo();
  modalOpen = true;
  currentVideoUrl = videoUrl;
});

tableWhatForMenu.addEventListener('click', function(){
  var videoUrl = 'https://vimeo.com/823322038';
  clearSelection();
  hideModalAudio();
  resetTable();
  createPlayerVideo(videoUrl);
  showModalVideo();
  modalOpen = true;
  currentVideoUrl = videoUrl;
});