## forgejo (git frontend)
This is alternative to github 

### setup
- it has it's own network and is connected to the external caddy network for domain to work
- data is stored in the default docker volumes. volume name is `forgejo_data`
- database uses in postgres and it also has volume named `forgejo_postgres`