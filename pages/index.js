const cardContainer = document.querySelector('.card-container');
const cards = cardContainer.querySelectorAll('.card');

function addCard() {
  const newCard = document.createElement('div');
  newCard.classList.add('card');
  // Add content to the new card

  if (cards.length % 4 === 0) {
    // If the number of cards is a multiple of 4, create a new row
    const newRow = document.createElement('div');
    newRow.classList.add('card-row');
    cardContainer.appendChild(newRow);
    newRow.appendChild(newCard);
  } else {
    // Otherwise, add the card to the last row
    const lastRow = cardContainer.lastElementChild;
    lastRow.appendChild(newCard);
  }
}