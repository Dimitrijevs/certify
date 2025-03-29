// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

// Login command
Cypress.Commands.add(
    "login",
    (email = "user@certify.com", password = "demopass") => {
        cy.visit(Cypress.config("loginUrl"));

        if (email !== "") {
            cy.get('input[id="data.email"]').type(email);
        }

        if (password !== "") {
            cy.get('input[id="data.password"]').type(password);
        }

        cy.get('button.fi-btn[type="submit"]').click();
    }
);

// Register command
Cypress.Commands.add(
    "register",
    (name = "Test User", email = generateRandomEmail(), password = "demopass") => {
        cy.visit(Cypress.config("registerUrl"));

        if (name !== "") {
            cy.get('input[id="data.name"]').type(name);
        }

        if (email !== "") {
            cy.get('input[id="data.email"]').type(email);
        }

        if (password !== "") {
            cy.get('input[id="data.password"]').type(password);
            cy.get('input[id="data.passwordConfirmation"]').type(password);
        }

        cy.get('button.fi-btn[type="submit"]').click();
    }
);

// Verify logged in state
Cypress.Commands.add("verifyLoggedIn", () => {
    cy.url().should("include", "app");
    cy.get("h1").should("have.class", "fi-header-heading");
});

// Verify login error
Cypress.Commands.add("verifyLoginError", () => {
    cy.get("p").should("have.class", "fi-fo-field-wrp-error-message");
});

// Verify registrated state
Cypress.Commands.add("verifyRegistered", () => {
    cy.url().should("include", "app");
    cy.get("h1").should("have.class", "fi-header-heading");
});

// Verify registration error
Cypress.Commands.add("verifyRegistrationError", () => {
    cy.get("p").should("have.class", "fi-fo-field-wrp-error-message");
});
