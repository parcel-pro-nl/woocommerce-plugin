const chars = 'abcdefghijklmnopqrstuvwxyz';

export function randomString(length = 10) {
  let result = '';
  while (result.length < length) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}
