import { testUsers, generateRandomEmail, generateRandomName } from "../../support/utils";

describe("Register Flow", () => {
    it("should register with valid credentials", () => {
        const randomEmail = generateRandomEmail();
        const randomName = generateRandomName();
        
        cy.visit(Cypress.config('registerUrl'));

        cy.register(randomName, randomEmail, testUsers.valid.password);

        cy.verifyRegistered();
    });

    it("should not register with already used email", () => {
        const randomName = generateRandomName();
        const usedEmail = testUsers.valid.email;

        cy.visit(Cypress.config('registerUrl'));

        cy.register(randomName, usedEmail, testUsers.valid.password);

        cy.verifyRegistrationError();
    });

    it("should not register with already used name", () => {
        const usedName = testUsers.valid.name;
        const randomEmail = generateRandomEmail();

        cy.visit(Cypress.config('registerUrl'));

        cy.register(usedName, randomEmail, testUsers.valid.password);

        cy.verifyRegistrationError();
    });
});