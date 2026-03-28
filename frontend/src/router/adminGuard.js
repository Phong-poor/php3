export default function adminGuard(to, from, next) {
  const user = JSON.parse(localStorage.getItem("user"));

  if (!user) {
    return next("/login");
  }

  if (user.role !== "admin") {
    return next("/");
  }

  return next();
}