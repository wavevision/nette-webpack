/* eslint @typescript-eslint/no-var-requires: 'off' */
const tsConfig = require('./tsconfig.json');

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
  coverageReporters: ['html', 'lcov', 'text'],
  collectCoverageFrom: [
    'src/assets/**/*.ts',
    '!**/*.d.ts',
    '!**/node_modules/**',
    '!src/assets/**/tests/**',
    '!src/assets/index.ts',
  ],
  preset: 'ts-jest',
  globals: {
    'ts-jest': {
      isolatedModules: true,
      tsconfig: {
        ...tsConfig.compilerOptions,
        lib: ['ES2019'],
        module: 'commonjs',
        target: 'ES2019',
      },
    },
  },
};
