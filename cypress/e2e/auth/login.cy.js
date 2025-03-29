import { testUsers, generateRandomEmail } from '../../support/utils';

describe("Login Valid Flow", () => {
    it("should log in with valid credentials", () => {
        // Generate random timestamp to ensure uniqueness
        cy.login(testUsers.valid.email, testUsers.valid.password);

        cy.verifyLoggedIn();
    });
});

describe("Login Invalid Flow", () => {
    it("should not log in with invalid credentials", () => {
        const randomEmail = generateRandomEmail();

        cy.login(randomEmail, testUsers.invalid.password);

        cy.verifyLoginError();
    });
});

describe("Logout Flow", () => {
    it("should log out successfully", () => {
        cy.login(testUsers.valid.email, testUsers.valid.password);

        cy.verifyLoggedIn();

        cy.get('button[aria-label="User menu"]').click();

        cy.get('button[style=";"]').click();
    });
});
