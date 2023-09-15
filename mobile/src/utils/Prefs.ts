import { Preferences } from '@capacitor/preferences'

export default class {

  constructor() {
    //
  }

  async delete(key: string): Promise<void> {
    return await Preferences.remove({ key })
  }

  async set(key: string, value: any): Promise<void> {
    return await Preferences.set({ key, value: JSON.stringify(value) })
  }

  async get(key: string): Promise<any|null> {
    return await Preferences.get({ key })
      .then(resp => (
        resp.value !== null
          ? JSON.parse(resp.value)
          : null
      ))
      .catch((err: Error) => {
        console.log(err)
        return null
      })
      .then((value: any|null) => value)
  }
}
