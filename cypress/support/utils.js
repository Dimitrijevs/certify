export const generateRandomEmail = () => {
    const timestamp = Date.now();
    return `testuser_${timestamp}@certify.com`;
};

export const generateRandomName = () => {
    const timestamp = Date.now();
    return `Test User ${timestamp}`;
}

export const testUsers = {
    valid: {
        email: "user@certify.com",
        password: "demopass",
    },
    invalid: {
        email: generateRandomEmail(),
        password: "wrongpassword",
    },
};
