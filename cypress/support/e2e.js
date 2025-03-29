// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'

Cypress.on("uncaught:exception", (err, runnable) => {
    // Ignore Livewire-specific errors
    if (err.message.includes("Snapshot missing") || err.message.includes("uri")) {
        return false; // Tells Cypress to skip the error, test keeps rollin’
    }
    
    // Let other errors fail the test
    return true;
});

beforeEach(() => {
    cy.viewport(1100, 750);
});