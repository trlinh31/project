import { Ability } from '@casl/ability'
import type { UserAbility } from './AppAbility'

export const initialAbility: UserAbility[] = [
  {
    action: 'read',
    subject: 'Auth',
  },
]

//  Read ability from localStorage
// 👉 Handles auto fetching previous abilities if already logged-in user
// ℹ️ You can update this if you store user abilities to more secure place
// ❗ Anyone can update localStorage so be careful and please update this
const stringifiesUserAbilities = localStorage.getItem('userAbilities')
const existingAbility = stringifiesUserAbilities ? JSON.parse(stringifiesUserAbilities) : null

export default new Ability(existingAbility || initialAbility)
