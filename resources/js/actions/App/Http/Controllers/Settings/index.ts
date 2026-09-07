import ProfileController from './ProfileController'
import SecurityController from './SecurityController'
import TimerController from './TimerController'
const Settings = {
    ProfileController: Object.assign(ProfileController, ProfileController),
SecurityController: Object.assign(SecurityController, SecurityController),
TimerController: Object.assign(TimerController, TimerController),
}

export default Settings