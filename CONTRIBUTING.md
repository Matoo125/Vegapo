Aby Vegapo aplikácia nebola iba funkčná ale aj ľahko rozšíritelná a upravitelná, je potrebné si dohodnúť určitý štandard kódovania, ktorý bude každý prispievatel dodržiavať. Všetky pravidlá a techniky budú predstavené v tomto súbore. 

## Zopár pravidiel
1. Každý súbor používa medzery namiesto tabu a šírka tabu sú 2 medzery.
2. Maximálna širka riadku je 80 stĺpcov.
3. Držíme sa  MVC architektúry.
4. Dodržiavame [DRY](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself) princíp.
5. Snažíme sa využívať existujúce metódy namiesto vytvárania nových.
6. Nebojíme sa pýtať otázky, ak nám niečo nieje jasné.
7. Prezreme si issues a wiki, aby sme zistili, či naša otázka už nepozná odpoveď.

## FAQ
_Čo spraviť ak nájdem existujúci kód so širkou prekračujúcou limit?_
- Rozdelím ho, aby tento limit spĺňal.

_Čo spraviť ak nájdem duplikovaný kód?_
- Pokúsim sa vytvoriť DRY code pomocou abstrakcie.

_Čo ak pracujem na súbore, ktorý má 4 medzery namiesto dvoch?_
- Pozriem si issues, či som jediný, kto pracuje v danom súbore. Ak áno preformátujem ho na 2 medzery.

