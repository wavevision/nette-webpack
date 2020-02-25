module.exports = {
  cacheDirectory: '<rootDir>/temp/cache/jest',
  testEnvironment: 'node',
  testMatch: ['<rootDir>/**/tests/**/*.test.ts'],
  testPathIgnorePatterns: [
    '<rootDir>/dist/',
    '<rootDir>/node_modules/',
    '<rootDir>/temp/',
  ],
  moduleFileExtensions: ['js', 'ts'],
  coverageDirectory: '<rootDir>/temp/coverage/ts',
  coverageReporters: ['html', 'text'],
  collectCoverageFrom: [
    'src/assets/**/*.ts',
    '!**/*.d.ts',
    '!**/node_modules/**',
    '!src/assets/**/tests/**',
    '!src/assets/index.ts',
  ],
  preset: 'ts-jest',
};
